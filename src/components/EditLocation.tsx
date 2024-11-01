// Library.
import {ChangeEvent, useEffect, useState} from 'react'
import {Item, Menu, TriggerEvent, useContextMenu} from 'react-contexify'
import {__} from '@wordpress/i18n'
// Ours.
import {Description, Field, FormTable, Header, Nonce, Row} from './form'
import {Marker, NaverMap} from './map'
// Types.
import {LocationProps} from '../types'
// Style.
import 'react-contexify/ReactContexify.css'
import '../styles/edit-location.css'

type Props = {
    data: LocationProps
    nonce: string,
    postId: number,
}

const MENU_ID = 'edit-location'

export default function EditLocation(props: Props) {
    const {show} = useContextMenu({
        id: MENU_ID,
    })

    const [map, setMap] = useState<naver.maps.Map | null>(null),
        [data, setData] = useState<LocationProps>(props.data),
        [isMarked, setMarked] = useState<boolean>(props.data.address.length > 0)

    // Make sure that the map is correctly displayed, especially when it is in a meta-box.
    useEffect(() => {
        setTimeout(() => {
            window.dispatchEvent(new Event('resize'))
        }, 100)
    }, [])

    return (
        <>
            <FormTable>
                <Row>
                    <Header>
                        {__('Map', 'shoplic-integration-for-naver-map')}
                    </Header>
                    <Field>
                        <NaverMap
                            id="edit-map"
                            className={`naver-map edit-map edit-map-${props.postId}`}
                            mapOptions={{
                                center: data.coord,
                                disableKineticPan: false,
                                tileTransition: true,
                            }}
                            onClick={(event: naver.maps.PointerEvent) => {
                                setData((prevState) => ({
                                    ...prevState,
                                    coord: {
                                        lat: event.coord.y,
                                        lng: event.coord.x,
                                    },
                                }))
                            }}
                            ref={(_map: naver.maps.Map | null) => setMap(() => _map)}
                            style={{
                                height: '480px'
                            }}
                        >
                            {map && data.coord &&
                                <Marker
                                    onRightClick={(event) => {
                                        show({
                                            event: event.pointerEvent as TriggerEvent
                                        })
                                    }}
                                    markerOptions={{
                                        map: map,
                                        position: data.coord,
                                    }}
                                />
                            }
                        </NaverMap>
                        <p id="coord-info"><span>
                            {__('Latitude', 'shoplic-integration-for-naver-map')}: {data.coord.lat.toFixed(4)},
                            {__('Longitude', 'shoplic-integration-for-naver-map')}: {data.coord.lng.toFixed(4)}</span>
                        </p>
                        <input
                            id="nm-location-coord-lat"
                            name="nm_location_coord[lat]"
                            type="hidden"
                            defaultValue={data.coord.lat}
                        />
                        <input
                            id="nm-location-coord-lngt"
                            name="nm_location_coord[lng]"
                            type="hidden"
                            defaultValue={data.coord.lng}
                        />
                        <Menu id={MENU_ID}>
                            <Item
                                id="address"
                                onClick={() => {
                                    if (
                                        data.address.length &&
                                        !confirm(__('The address is entered. Overwrite?', 'shoplic-integration-for-naver-map'))
                                    ) {
                                        return
                                    }
                                    naver.maps.Service.reverseGeocode({
                                        coords: new naver.maps.LatLng(data.coord.lat, data.coord.lng),
                                        orders: [
                                            naver.maps.Service.OrderType.ROAD_ADDR,
                                            naver.maps.Service.OrderType.ADDR,
                                        ].join(',')
                                    }, (status, response) => {
                                        if (naver.maps.Service.Status.ERROR === status) {
                                            return alert('Something Wrong!')
                                        }

                                        const {roadAddress, jibunAddress} = response.v2.address

                                        if (roadAddress.length) {
                                            setData((prevState) => ({
                                                ...prevState,
                                                address: roadAddress,
                                            }))
                                        } else if (jibunAddress.length) {
                                            setData((prevState) => ({
                                                ...prevState,
                                                address: jibunAddress,
                                            }))
                                        } else {
                                            return alert('Address not found.')
                                        }
                                    })
                                }}
                            >{__('Enter an address for this location', 'shoplic-integration-for-naver-map')}</Item>
                        </Menu>
                    </Field>
                </Row>
                <Row>
                    <Header>
                        <label htmlFor="nm-location-address">
                            {__('Address', 'shoplic-integration-for-naver-map')}
                        </label>
                    </Header>
                    <Field>
                        <div className="address-wrap">
                            <input
                                className="text"
                                id="nm-location-address"
                                name="nm_location_address"
                                onChange={(e: ChangeEvent) => {
                                    setData((prevState) => {
                                        return {
                                            ...prevState,
                                            address: (e.target as HTMLInputElement).value,
                                        }
                                    })
                                }}
                                type="text"
                                value={data.address}
                            />
                            <button
                                className="button button-primary"
                                disabled={!data.address.length || data.address === props.data.address}
                                type="button"
                                onClick={() => {
                                    if (isMarked && !confirm(__('Do you want to search address again?', 'shoplic-integration-for-naver-map'))) {
                                        return
                                    }

                                    naver.maps.Service.geocode({
                                        query: data.address,
                                    }, (status, response) => {
                                        if (naver.maps.Service.Status.ERROR === status) {
                                            return alert(__('Failed to search address.', 'shoplic-integration-for-naver-map'))
                                        }
                                        if (response.v2.meta.totalCount === 0) {
                                            return alert('totalCount' + response.v2.meta.totalCount)
                                        }
                                        const coord = {
                                            lat: parseFloat(response.v2.addresses[0].y),
                                            lng: parseFloat(response.v2.addresses[0].x),
                                        }
                                        setData((prevState) => ({
                                            ...prevState,
                                            coord
                                        }))
                                        if (map) {
                                            map.setCenter(coord)
                                        }
                                        if (!isMarked) {
                                            setMarked(true)
                                        }
                                    })
                                }}
                            >마커 이동
                            </button>
                        </div>
                        <Description>
                            {__('You may need to enter a detailed address yourself.', 'shoplic-integration-for-naver-map')}
                        </Description>
                    </Field>
                </Row>
                <Row>
                    <Header>
                        <label htmlFor="nm-location-telephone">
                            {__('Telephone', 'shoplic-integration-for-naver-map')}
                        </label>
                    </Header>
                    <Field>
                        <input
                            className="text large-text"
                            id="nm-location-telephone"
                            name="nm_location_telephone"
                            onChange={(e: ChangeEvent) => {
                                const value = (e.target as HTMLInputElement).value.replace(/[^0-9\\-]/g, '')
                                setData((prevState) => {
                                    return {
                                        ...prevState,
                                        telephone: value,
                                    }
                                })
                            }}
                            placeholder="000-0000-0000"
                            type="tel"
                            value={data.telephone}
                        />
                        <Description>
                            {__('Phone number at this location.', 'shoplic-integration-for-naver-map')}
                        </Description>
                    </Field>
                </Row>
                <Row>
                    <Header>
                        <label htmlFor="nm-location-url">
                            {__('URL', 'shoplic-integration-for-naver-map')}
                        </label>
                    </Header>
                    <Field>
                        <input
                            className="text large-text"
                            id="nm-location-url"
                            name="nm_location_url"
                            onChange={(e: ChangeEvent) => {
                                setData((prevState) => {
                                    return {
                                        ...prevState,
                                        url: (e.target as HTMLInputElement).value,
                                    }
                                })
                            }}
                            placeholder="https://...."
                            type="text"
                            value={data.url}
                        />
                        <Description>
                            {__('URL related to this location.', 'shoplic-integration-for-naver-map')}
                        </Description>
                    </Field>
                </Row>
                <Row>
                    <Header>
                        <label htmlFor="">
                            {__('Alternative Title', 'shoplic-integration-for-naver-map')}
                        </label>
                    </Header>
                    <Field>
                        <input
                            className="text large-text"
                            id="nm-location-alt-title"
                            name="nm_location_alt_title"
                            onChange={(e: ChangeEvent) => {
                                setData((prevState) => {
                                    return {
                                        ...prevState,
                                        alt_title: (e.target as HTMLInputElement).value,
                                    }
                                })
                            }}
                            type="text"
                            value={data.alt_title}
                        />
                        <Description>
                            {__('The title you want to use. Leave blank to use the post title.', 'shoplic-integration-for-naver-map')}
                        </Description>
                    </Field>
                </Row>
            </FormTable>
            {/* Nonce is here! */}
            <Nonce
                id="nm-location-nonce"
                name="nm_location_nonce"
                defaultValue={props.nonce}
            />
        </>
    )
}
