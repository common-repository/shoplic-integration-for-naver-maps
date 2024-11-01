import {ForwardedRef, forwardRef, useImperativeHandle, useLayoutEffect, useRef} from 'react'

type Props = {
    markerOptions?: naver.maps.MarkerOptions
    onClick?: (event: naver.maps.PointerEvent) => void
    onRightClick?: (event: naver.maps.PointerEvent) => void
}

const Marker = forwardRef(function Marker(props: Props, ref: ForwardedRef<naver.maps.Marker | null>) {
    const {markerOptions} = props,
        marker = useRef<naver.maps.Marker | null>(null)

    useLayoutEffect(() => {
        const listeners: naver.maps.MapEventListener[] = []
        if (markerOptions) {
            marker.current = new naver.maps.Marker(markerOptions)
            if (props.onClick) {
                listeners.push(marker.current!.addListener('click', props.onClick))
            }
            if (props.onRightClick) {
                listeners.push(marker.current!.addListener('rightclick', props.onRightClick))
            }
        }
        return () => {
            naver.maps.Event.removeListener(listeners)
            if (marker.current) {
                marker.current.setMap(null)
            }
        }
    }, [markerOptions])

    useImperativeHandle<naver.maps.Marker | null, naver.maps.Marker | null>(ref, () => marker.current)

    return <></>
})

export default Marker
