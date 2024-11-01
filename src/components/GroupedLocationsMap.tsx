import {useState} from 'react'
import {__} from '@wordpress/i18n'
// Ours
import InfoPanel from './info-panel/InfoPanel.tsx'
import NaverMap from './map/NaverMap.tsx'
import Marker from './map/Marker.tsx'
import {getCenter, getIconSize} from '../libs/helper.ts'
// Types.
import {GroupedLocationMapObjectValue, MapLocationProps} from '../types'
// Styles.
import '../styles/grouped-locations-map.css'

type Props = GroupedLocationMapObjectValue

const {
    size,
    scaledSize,
    origin,
    anchor,
} = getIconSize()

export default function GroupedLocationMap(props: Props) {
    const {
        atts: {
            height,
            post_id,
            width,
            zoom,
        },
        data,
        mapIcons: {
            normal: normalMarker,
            selected: selectedMarker,
        },
    } = props

    const [map, setMap] = useState<naver.maps.Map | null>(null),
        [selected, setSelected] = useState<number>(-1)

    if (0 === data.length) {
        return (
            <div className="naver-map error-message">
                {__('[네이버 지도 에러] 지도에 위치가 설정되지 않았습니다.', 'naver_maps')}
            </div>
        )
    }

    return (
        <>
            <NaverMap
                id={`naver-map-${post_id}`}
                className={`naver-map grouped-locations-map grouped-locations-map-${post_id}`}
                mapOptions={{
                    center: getCenter(data.map((loc) => loc.coord)),
                    disableKineticPan: false,
                    zoom: zoom,
                }}
                ref={(_map: naver.maps.Map | null) => setMap(() => _map)}
                style={{
                    height,
                    width,
                }}
            >
                {map && data.map((location: MapLocationProps, index: number) => {
                    return (
                        <Marker
                            key={location.id}
                            markerOptions={{
                                icon: {
                                    anchor,
                                    origin,
                                    scaledSize,
                                    size,
                                    url: index === selected ? selectedMarker : normalMarker,
                                },
                                map: map,
                                position: location.coord,
                                title: location.alt_title.length > 0 ? location.alt_title : location.title,
                            }}
                            onClick={() => {
                                if (index === selected) {
                                    setSelected(-1)
                                } else {
                                    setSelected(index)
                                }
                            }}
                        />
                    )
                })}
            </NaverMap>
            <InfoPanel
                mapLocationProps={selected > -1 ? data[selected] : undefined}
                onClose={() => setSelected(-1)}
            />
        </>
    )
}
