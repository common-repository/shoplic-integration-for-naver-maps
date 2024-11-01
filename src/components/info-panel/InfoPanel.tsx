import {joinClassNames} from '../../libs/helper.ts'
import {MapLocationProps} from '../../types'

type Props = {
    mapLocationProps?: MapLocationProps
    onClose?: () => void
}

export default function InfoPanel(props: Props) {
    const {
        mapLocationProps: location,
        onClose,
    } = props

    return (
        <div className={joinClassNames('info-panel-wrap', location ? 'slide-up' : '')}>
            <div className="info-panel">
                {location && (
                    <>
                        <section className="header">
                            <h3>{location.alt_title.length > 0 ? location.alt_title : location.title}</h3>
                            <span className="close" onClick={() => {
                                onClose && onClose()
                            }}>&times;</span>
                        </section>
                        <section className="body">
                            <address>
                                <span className="address">📍 {location.address}</span>
                                {location.telephone &&
                                    <span className="telephone">☎️
                                        <a href={`tel://${location.telephone}`}>
                                            {location.telephone}
                                        </a>
                                    </span>}
                                {location.url &&
                                    <span className="url">🔗 <a href={location.url} target="_blank" rel="extermal nofollow noreferrer">링크</a></span>}
                            </address>
                        </section>
                    </>
                )}
            </div>
        </div>
    )
}
