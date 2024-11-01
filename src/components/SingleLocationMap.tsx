import {useState} from 'react'
// Src
import CustomNaverMap from './map/NaverMap.tsx'
import CustomMarker from './map/Marker.tsx'
// Types
import {SingleLocationMapObjectValue} from '../types'
import {getIconSize} from '../libs/helper.ts'

type Props = SingleLocationMapObjectValue

const {
    size,
    scaledSize,
    origin,
    anchor,
} = getIconSize()

export default function SingleLocationMap(props: Props) {
    const {
        atts: {
            height,
            post_id,
            width,
            zoom,
        },
        data: {
            alt_title,
            coord,
        },
        mapIcon,
    } = props

    const [map, setMap] = useState<naver.maps.Map | null>(null)

    return (
        <CustomNaverMap
            id={`naver-map-${post_id}`}
            className={`naver-map single-location-map single-location-map-${post_id}`}
            mapOptions={{
                center: coord,
                disableKineticPan: false,
                zoom: zoom,
            }}
            ref={(_map: naver.maps.Map | null) => {
                setMap(() => _map)
            }}
            style={{
                height: height,
                width: width,
            }}
        >
            {map && <CustomMarker
                markerOptions={{
                    icon: {
                        anchor,
                        origin,
                        scaledSize,
                        size,
                        url: mapIcon,
                    },
                    map: map,
                    position: coord,
                    title: alt_title,
                }}
            />}
        </CustomNaverMap>
    )
}
