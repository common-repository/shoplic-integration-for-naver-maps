<?php

namespace Shoplic\NaverMap;

use Closure;
use ReflectionClass;
use ReflectionException;
use Shoplic\NaverMap\Modules\AdminListTableFields;
use Shoplic\NaverMap\Modules\AdminSettings;
use Shoplic\NaverMap\Modules\CustomFieldGroups;
use Shoplic\NaverMap\Modules\CustomFields;
use Shoplic\NaverMap\Modules\CustomPosts;
use Shoplic\NaverMap\Modules\CustomTaxonomies;
use Shoplic\NaverMap\Modules\Edit;
use Shoplic\NaverMap\Modules\L10n;
use Shoplic\NaverMap\Modules\Module;
use Shoplic\NaverMap\Modules\ModuleException;
use Shoplic\NaverMap\Modules\ScriptLoader;
use Shoplic\NaverMap\Modules\Settings;
use Shoplic\NaverMap\Modules\Shortcodes;

/**
 * Available inner modules
 *
 * @property-read AdminSettings        $adminSettings
 * @property-read AdminListTableFields $adminListTableFields
 * @property-read CustomFieldGroups    $customFieldGroups
 * @property-read CustomFields         $customFields
 * @property-read CustomPosts          $customPosts
 * @property-read CustomTaxonomies     $customTaxonomies
 * @property-read Edit                 $edit
 * @property-read L10n                 $l10n
 * @property-read ScriptLoader         $scriptLoader
 * @property-read Settings             $settings
 * @property-read Shortcodes           $shortcodes
 */
final class Container
{
    private const PREFIX = 'Shoplic\\NaverMap\\Modules\\';

    /** @var Container */
    private static $instance = null;

    /** @var Module[] */
    private $modules = [];

    private $resolved = [];

    /** @var (Module|object|null)[] */
    private $store = [];

    /** @var (array|Closure)[] */
    private $args;

    public static function getInstance(): Container
    {
        if (is_null(self::$instance)) {
            self::$instance = new Container();
        }

        return self::$instance;
    }

    public function __get(string $name)
    {
        return $this->modules[$name] ?? null;
    }

    public function __set(string $name, $value)
    {
        $this->modules[$name] = $value;
    }

    public function __isset(string $name)
    {
        return isset($this->modules[$name]);
    }

    /**
     * @template TClassName
     * @param class-string<TClassName> $identifier The identifier of the value to retrieve.
     *
     * @return TClassName|null The retrieved value associated with the given identifier, or null if not found.
     */
    public function get(string $identifier): object
    {
        if ( ! $this->has($identifier)) {
            try {
                $value = $this->instantiate($identifier);
            } catch (ModuleException|ReflectionException $e) {
                $value = null;
            }
            $this->store[$identifier] = $value;
        }

        return $this->store[$identifier];
    }

    public function has(string $identifier): bool
    {
        return isset($this->store[$identifier]);
    }

    public function set(string $identifier, $value)
    {
        if (is_null($value)) {
            unset($this->store[$identifier]);
            return;
        }

        $this->store[$identifier] = $value;
    }

    private function __construct()
    {
        $this->args = $this->loadModuleArgs();

        $this->initModules($this->loadModulePlans());
    }

    private function loadModuleArgs(): array
    {
        return include(plugin_dir_path(NM_MAIN) . 'conf/module-args.php');
    }

    private function loadModulePlans(): array
    {
        return include plugin_dir_path(NM_MAIN) . 'conf/module-setup.php';
    }

    private function initModules(array $moduleSetup): void
    {
        foreach ($moduleSetup as $hook => $chunks) {
            foreach ($chunks as $priority => $plans) {
                foreach ($plans as $plan) {
                    $module = '';
                    $name   = '';

                    if (is_array($plan)) {
                        $module = $plan['module'] ?? '';
                        $name   = $plan['name'] ?? '';
                    } elseif (is_string($plan)) {
                        $module = $plan;
                    }

                    if ($module) {
                        if ( ! $name) {
                            $name = strtolower($module[0]) . substr($module, 1);
                        }
                        /**
                         * @uses AdminListTableFields
                         * @uses AdminSettings
                         * @uses ContentFilter
                         * @uses CustomFieldGroups
                         * @uses CustomFields
                         * @uses CustomPosts
                         * @uses CustomTaxonomies
                         * @uses Edit
                         * @uses ScriptLoader
                         * @uses Settings
                         * @uses Shortcodes
                         */
                        add_action($hook, $this->bindModule($module, $name), (int)$priority);
                    }
                }
            }
        }
    }

    /**
     * Instantiate modules and bind them to filters, and actions.
     *
     * @param string $handler
     * @param string $name
     *
     * @return Closure
     */
    private function bindModule(string $handler, string $name = ''): Closure
    {
        return function () use ($handler, $name) {
            $split = explode('@', $handler, 2);
            $count = count($split);
            $args  = func_get_args();

            if (1 === $count) {
                $split = array_shift($split);
                if (is_callable($split)) {
                    return call_user_func_array($split, $args);
                } else {
                    try {
                        return $this->instantiateModule($split, $name);
                    } catch (ModuleException $e) {
                        // Pass.
                    }
                }
            } elseif (2 === $count) {
                $split[0] = $this->instantiateModule($split[0], $name);
                if (is_callable($split)) {
                    return call_user_func_array($split, $args);
                }
            }
            throw new ModuleException("Handler $handler not available!");
        };
    }

    /**
     * @throws ReflectionException
     * @throws ModuleException
     */
    private function instantiate(string $fqn): object
    {
        // If $fqn were a module, we have located it. We can get identifier by $fqn.
        $identifier = $this->resolved[$fqn] ?? null;

        if ($identifier) {
            // Module can have arguments.
            $args = $this->args[$identifier] ?? [];
        } else {
            // General instances.
            $args                 = null;
            $this->resolved[$fqn] = $fqn;
        }

        if (is_callable($args)) {
            $args = $args($this, $identifier);
        } elseif (is_null($args)) {
            $ref         = new ReflectionClass($fqn);
            $constructor = $ref->getConstructor();
            $params      = $constructor ? $constructor->getParameters() : [];
            $args        = [];

            foreach ($params as $param) {
                $optional = $param->isOptional();
                $typeName = $param->getType()->getName();
                $nullable = $param->getType()->allowsNull();
                $builtin  = $param->getType()->isBuiltin();

                if ($builtin) {
                    if ($optional) {
                        $args[] = $param->getDefaultValue();
                    } elseif ($nullable) {
                        $args[] = null;
                    } else {
                        throw new ModuleException();
                    }
                    continue;
                }

                // Remove heading '?' for optional parameters.
                if ($nullable && '?' === $typeName[0]) {
                    $typeName = substr($typeName, 1);
                }

                // Is this name already resolved?
                if (isset($this->resolved[$typeName])) {
                    $args[] = $typeName === $this->resolved[$typeName] ?
                        $this->store[$typeName] :
                        $this->modules[$this->resolved[$typeName]];
                    continue;
                }

                // Does this name should be resolved now?
                if (class_exists($typeName)) {
                    $this->resolved[$typeName] = $typeName;
                    $args[]                    = $this->instantiate($typeName);
                } else {
                    throw new ModuleException();
                }
            }
        }

        return new $fqn(...$args);
    }

    /**
     * @param string $identifier
     * @param string $name
     *
     * @return Module
     * @throws ModuleException
     * @throws ReflectionException
     */
    private function instantiateModule(string $identifier, string $name): Module
    {
        if ( ! isset($this->$identifier)) {
            $fqn = $this->locateModule($identifier);

            // Before instantiation.
            $this->resolved[$fqn] = $identifier;

            $instance = $this->instantiate($fqn);

            $this->$identifier = $instance;
            if ($name) {
                $this->$name = $instance;
            }
        }

        return $this->$identifier;
    }

    /**
     * @throws ModuleException
     */
    private function locateModule(string $identifier): string
    {
        $prefix = self::PREFIX;
        $fqn    = "$prefix$identifier";

        if ( ! self::isValidModule($fqn)) {
            throw new ModuleException("Identifier $identifier not available.");
        }

        return $fqn;
    }

    public static function isValidModule(string $fqn): bool
    {
        return class_exists($fqn) && ($impl = class_implements($fqn)) && isset($impl[Module::class]);
    }

    public static function getIdentfier(string $fqn): string
    {
        $fqn = ltrim($fqn, '\\');

        if (str_starts_with($fqn, self::PREFIX)) {
            return substr($fqn, 26);
        }

        return '';
    }
}
