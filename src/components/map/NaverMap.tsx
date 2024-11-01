import {
    CSSProperties,
    ForwardedRef,
    forwardRef,
    PropsWithChildren,
    useImperativeHandle,
    useLayoutEffect,
    useRef
} from 'react'

type Props = PropsWithChildren<{
    id?: string
    className?: string
    onClick?: (event: naver.maps.PointerEvent) => void
    onRightClick?: (event: naver.maps.PointerEvent) => void
    mapOptions?: naver.maps.MapOptions
    style?: CSSProperties
}>

const NaverMap = forwardRef(function NaverMap(props: Props, ref: ForwardedRef<naver.maps.Map | null>) {
    const {id, className, mapOptions, style} = props

    const divRef = useRef<HTMLDivElement>(null),
        mapRef = useRef<naver.maps.Map | null>(null)

    useLayoutEffect(() => {
        const listeners: naver.maps.MapEventListener[] = []
        if (divRef.current) {
            mapRef.current = new naver.maps.Map(divRef.current, mapOptions)
            if (props.onClick) {
                listeners.push(mapRef.current!.addListener('click', props.onClick))
            }
            if (props.onRightClick) {
                listeners.push(mapRef.current!.addListener('rightclick', props.onRightClick))
            }
        }
        return () => {
            naver.maps.Event.removeListener(listeners)
        }
    }, [divRef.current])

    useImperativeHandle<naver.maps.Map | null, naver.maps.Map | null>(ref, () => mapRef.current)

    return (
        // Wrapper
        <div id={id} className={className} style={style}>
            {/* Map */}
            <div ref={divRef} style={style}/>
            {props.children}
        </div>
    )
})

export default NaverMap
