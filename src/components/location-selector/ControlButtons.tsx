import {__} from '@wordpress/i18n'

type Props = {
    onClickAdd?: () => void
    onClickRemove?: () => void
}

export default function ControlButtons(props: Props) {
    const {onClickAdd, onClickRemove} = props

    return (
        <div className="control-buttons">
            <button
                className="button"
                onClick={() => onClickRemove && onClickRemove()}
                title={__('Exclude', 'shoplic-integration-for-naver-map')}
                type="button"
            >
                &#9664;
            </button>
            <button
                className="button"
                onClick={() => onClickAdd && onClickAdd()}
                title={__('Include', 'shoplic-integration-for-naver-map')}
                type="button"
            >
                &#9654;
            </button>
        </div>
    )
}
