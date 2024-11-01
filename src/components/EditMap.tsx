// Library
import {useState} from 'react'
import {createInterpolateElement} from '@wordpress/element'
import {__} from '@wordpress/i18n'
// Ours.
import LocationSelector from './LocationSelector.tsx'
import {Description, Nonce} from './form'
import {NaverMap, Marker} from './map'
import {getCenter} from '../libs/helper.ts'
// Types.
import {LocationSet, MapProps} from '../types'
// Style.
import '../styles/edit-map.css'

type Props = {
    data: MapProps
    nonce: string
    postId: number
}

export default function EditMap(props: Props) {
    const [locations, setLocations] = useState<LocationSet>(new Set(props.data.locations)),
        [map, setMap] = useState<naver.maps.Map | null>(null)

    return (
        <div id="edit-map-wrap">
            <NaverMap
                id="edit-map"
                className={`naver-map edit-map edit-map-${props.postId}`}
                mapOptions={{
                    center: getCenter(props.data.allLocations.map((location) => location.coord)),
                    disableKineticPan: false,
                    tileTransition: true,
                    zoom: 10
                }}
                ref={(_map: naver.maps.Map | null) => setMap(() => _map)}
                style={{
                    height: '480px'
                }}
            >
                {
                    map && props.data.allLocations
                        .filter((location) => locations.has(location.id))
                        .map((location) => {
                            return (
                                <Marker
                                    key={location.id}
                                    markerOptions={{
                                        map: map,
                                        position: location.coord
                                    }}
                                />
                            )
                        })
                }
            </NaverMap>
            <LocationSelector
                locations={props.data.allLocations}
                onChange={(values: LocationSet) => {
                    setLocations(values)
                }}
                values={locations}
            />
            <Description>
                {createInterpolateElement(
                    __('<kbd>Ctrl</kbd> 키를 눌러 복수의 항목을 선택할 수 있습니다.', 'naver_maps'),
                    {kbd: <kbd/>}
                )}
            </Description>
            {/* Hidden form fields. */}
            {[...locations.values()].map((location: number, idx: number) => (
                <input
                    id={`map-locations-${idx}`}
                    key={idx}
                    name="nm_map_locations[]"
                    type="hidden"
                    defaultValue={location}
                />
            ))}
            {/* Nonce is here! */}
            <Nonce
                id="nm-map-nonce"
                name="nm_map_nonce"
                defaultValue={props.nonce}
            />
        </div>
    )
}
