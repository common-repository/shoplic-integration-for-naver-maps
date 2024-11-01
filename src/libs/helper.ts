import {Coord} from '../types'
import {setLocaleData} from '@wordpress/i18n'

function joinClassNames(className: string, ...args: (string | null | undefined)[]): string {
    args = args.filter((arg) => arg && arg.length)
    return className + (args.length ? (' ' + args.join(' ')) : '')
}

function getCenter(markers: Coord[] | undefined): naver.maps.LatLng {
    if (markers && markers.length > 0) {
        const m0 = new naver.maps.LatLng(markers[0]),
            bounding = new naver.maps.LatLngBounds(m0, m0)

        for (let i = 1; i < markers.length; ++i) {
            bounding.extend(new naver.maps.LatLng(markers[i]))
        }

        return bounding.getCenter()
    }

    // Return default coord.
    return new naver.maps.LatLng(37.50112047582572, 127.02594679999856)
}

function getIconSize() {
    return {
        size: {height: 64, width: 64},
        scaledSize: {height: 32, width: 32},
        origin: {x: 0, y: 0},
        anchor: {x: 16, y: 32},
    }
}

function l10nHelper(...domains: string[]): void {
    domains.forEach((domain) => {
        const localeData = wp.i18n.getLocaleData(domains[0])
        if(localeData) {
            setLocaleData(localeData, domain)
        }
    })
}

export {
    getCenter,
    getIconSize,
    l10nHelper,
    joinClassNames,
}
