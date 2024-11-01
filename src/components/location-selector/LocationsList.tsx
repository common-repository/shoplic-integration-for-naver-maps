import {joinClassNames} from '../../libs/helper.ts'
import {LocationSet, MapLocationProps} from '../../types'

type Props = {
    locations?: MapLocationProps[]
    className?: string
    label?: string
    name?: string
    onChange?: (ids: number[]) => void
    selected?: LocationSet
}

function formatMapLocation(mapLocation: MapLocationProps) {
    let output = mapLocation.title

    if (mapLocation.address) {
        output += ` (${mapLocation.address})`
    }

    return output
}

export default function LocationsList(props: Props) {
    const {
        locations,
        className,
        label,
        name,
        onChange,
        selected,
    } = props

    return (
        <div className={`${joinClassNames('locations-list-wrap', className)}`}>
            <h3 className="label">{label}</h3>
            <select
                className={`${joinClassNames('locations-list', className)}`}
                multiple={true}
                name={name}
                onChange={(e) => {
                    const selected: number[] = []
                    for (const option of e.target.selectedOptions) {
                        const value = parseInt(option.value)
                        if (!isNaN(value)) {
                            selected.push(value)
                        }
                    }
                    onChange && onChange(selected)
                }}
                value={[...(selected?.values() ?? [])].map((value) => value.toString())}
            >
                {locations?.map((mapLocation: MapLocationProps) => (
                    <option
                        key={mapLocation.id}
                        title={formatMapLocation(mapLocation)}
                        value={mapLocation.id}
                    >
                        {formatMapLocation(mapLocation)}
                    </option>
                ))}
            </select>
        </div>
    )
}
