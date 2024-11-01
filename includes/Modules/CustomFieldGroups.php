<?php

namespace Shoplic\NaverMap\Modules;

final class CustomFieldGroups implements Module
{
    /**
     * @param int    $postId
     * @param string $context
     *
     * @return array{
     *     address: string,
     *     alt_title: string,
     *     coord: array{
     *      lat:int|string,
     *      lng:int|string
     *     },
     *     telephone: string,
     *     url: string
     * }
     */
    public function getLocationData(int $postId, string $context = 'raw'): array
    {
        $cf = nm()->customFields;

        $data = [
            'address'   => $cf->locationAddress->get($postId),
            'alt_title' => $cf->locationAltTitle->get($postId),
            'coord'     => $cf->locationCoord->get($postId),
            'telephone' => $cf->locationTelephone->get($postId),
            'url'       => $cf->locationUrl->get($postId),
        ];

        if ('display' === $context) {
            if (empty($data['alt_title'])) {
                $data['alt_title'] = get_the_title($postId);
            }
        }

        return $data;
    }

    public function updateLocationData(
        int $postId,
        string $address,
        string $altTitle,
        $coordLat,
        $coordLng,
        string $telephone,
        string $url
    ): void {
        $cf = nm()->customFields;

        $cf->locationAddress->update($postId, $address);
        $cf->locationAltTitle->update($postId, $altTitle);
        $cf->locationCoord->update($postId, [$coordLat, $coordLng]);
        $cf->locationTelephone->update($postId, $telephone);
        $cf->locationUrl->update($postId, $url);
    }

    /**
     * @param int    $postId
     *
     * @return array{
     *   locations:int[],
     * }
     */
    public function getMapData(int $postId): array
    {
        $cf = nm()->customFields;

        return [
            'locations' => $cf->mapLocations->get($postId),
        ];
    }

    /**
     * @param int          $postId
     * @param int|string[] $locations
     *
     * @return void
     */
    public function updateMapData(int $postId, array $locations)
    {
        $cf = nm()->customFields;

        $cf->mapLocations->update($postId, $locations);
    }
}
