// Library.
import {useState} from 'react'
import {__} from '@wordpress/i18n'
// Ours.
import ControlButtons from './location-selector/ControlButtons'
import LocationsList from './location-selector/LocationsList'
// Types.
import {LocationSet, MapLocationProps} from '../types'

type Props = {
    locations: MapLocationProps[]
    onChange: (values: LocationSet) => void
    values: LocationSet
}

export default function LocationSelector(props: Props) {
    const {
        locations: propLocations,
        onChange,
        values: propValues,
    } = props

    const [allLocations, setAllLocations] = useState<LocationSet>(new Set<number>()),
        [selectedLocations, setSelectedLocations] = useState<LocationSet>(new Set<number>())

    return (
        <div className="location-selector-wrap">
            <LocationsList
                className="all-locations"
                label={__('Available locations', 'shoplic-integration-for-naver-map')}
                locations={propLocations.filter((location) => !propValues.has(location.id))}
                onChange={(values: number[]) => {
                    setAllLocations(new Set(values))
                }}
                selected={allLocations}
            />
            <ControlButtons
                onClickAdd={() => {
                    // Setup new values.
                    const newValues = new Set(propValues)
                    allLocations.forEach((id) => {
                        if (!newValues.has(id)) {
                            newValues.add(id)
                        }
                    })
                    onChange(newValues)
                    // Reset.
                    setAllLocations(new Set())
                }}
                onClickRemove={() => {
                    // Setup new values.
                    const newValues = new Set(propValues)
                    selectedLocations.forEach((id) => {
                        if (newValues.has(id)) {
                            newValues.delete(id)
                        }
                    })
                    onChange(newValues)
                    // Reset.
                    setSelectedLocations(new Set())
                }}
            />
            <LocationsList
                className="selected-locations"
                label={__('Selected location', 'shoplic-integration-for-naver-map')}
                locations={propLocations.filter((location) => propValues.has(location.id))}
                onChange={(values: number[]) => {
                    setSelectedLocations(new Set(values))
                }}
                selected={selectedLocations}
            />
        </div>
    )
}
