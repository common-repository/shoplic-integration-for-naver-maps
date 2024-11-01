import {getLocaleData, setLocaleData} from '@wordpress/i18n'


declare global {
    const nmEditLocation: {
        data: LocationProps
        nonce: string
        post_id: string
    }

    const nmEditMap: {
        data: MapProps
        nonce: string
        post_id: string
    }

    interface Window {
        [key: string]: string | object
    }

    const wp: {
        i18n: {
            getLocaleData: getLocaleData
            setLocaleData: setLocaleData
        }
    }
}

type Coord = {
    lat: number
    lng: number
}

type LocationProps = {
    address: string
    alt_title: string
    coord: Coord
    telephone: string
    url: string
}

type MapProps = {
    allLocations: MapLocationProps[]
    locations: number[]
}

type MapLocationProps = {
    id: number
    status: string
    title: string
    type: string
} & LocationProps

type LocationSet = Set<number>

type ShortcodeAtts = {
    height: string
    post_id: number
    width: string
    zoom: number
}

type SingleLocationMapObjectValue = {
    atts: ShortcodeAtts
    data: LocationProps
    mapIcon: string
}

type GroupedLocationMapObjectValue = {
    atts: ShortcodeAtts
    data: MapLocationProps[]
    mapIcons: {
        normal: string
        selected: string
    }
}

export type {
    Coord,
    GroupedLocationMapObjectValue,
    LocationProps,
    LocationSet,
    MapLocationProps,
    MapProps,
    MapRefProps,
    ShortcodeAtts,
    SingleLocationMapObjectValue,
}
