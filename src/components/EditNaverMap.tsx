// Library
import {useEffect, useState} from 'react'
// Ours.
import {Marker, NaverMap} from './map'
// Types.
import {Coord} from '../types'

type Props = {
    center: Coord
    marker?: Coord
    onClick?: (event: naver.maps.PointerEvent) => void
    onMarkerRightClick?: (event: naver.maps.PointerEvent) => void
    postId: number
}

export default function EditNaverMap(props: Props) {
    const {
        center,
        marker,
        onClick,
        onMarkerRightClick,
        postId
    } = props

    const [map, setMap] = useState<naver.maps.Map | null>(null)

    useEffect(() => {
        const clickListener: naver.maps.MapEventListener | null = map ? map.addListener(
            'click',
            (e: naver.maps.PointerEvent) => onClick && onClick(e)
        ) : null

        return () => {
            if (map && clickListener) {
                map.removeListener(clickListener)
            }
        }
    }, [map])

    return (
        <NaverMap
            id="edit-map"
            className={`naver-map edit-map edit-map-${postId}`}
            mapOptions={{
                center: center,
                disableKineticPan: false,
                tileTransition: true,
            }}
            ref={(_map: naver.maps.Map | null) => setMap(() => _map)}
        >
            {map && marker &&
                <Marker
                    onRightClick={(event) => {
                        onMarkerRightClick && onMarkerRightClick(event)
                    }}
                    markerOptions={{
                        map: map,
                        position: marker,
                    }}
                />
            }
        </NaverMap>
    )
}
