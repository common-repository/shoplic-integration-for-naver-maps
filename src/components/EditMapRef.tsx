import {Field, FormTable, Header, Nonce, Row} from './form'
import {__, sprintf, _n} from '@wordpress/i18n'
import {ChangeEvent, useState} from 'react'

declare global {
    const nmEditMapRef: {
        data: RefData
        nonce: string
        post_id: string
    }
}

type BaseData = {
    post_id: number
    post_status: 'publish' | 'private'
    title: string
}

type RefData = {
    locations: { [key: number]: { address: string } & BaseData }
    maps: ({ locations: number[] } & BaseData)[]
    selected: number
}

type Props = {
    data: RefData
    nonce: string
    postId: string
}

export default function EditMapRef(props: Props) {
    const {
        data: {
            locations,
            maps,
        },
        nonce,
    } = props

    const [selected, setSelected] = useState<number>(props.data.selected)

    return (
        <>
            <FormTable>
                <Row>
                    <Header>
                        <label htmlFor="nm-map-ref-selection">
                            {__('Location or map to refer to', 'shoplic-integration-for-naver-map')}
                        </label>
                    </Header>
                    <Field>
                        <select
                            id="nm-ref"
                            name="nm_ref"
                            onChange={(e: ChangeEvent) => {
                                const value = parseInt((e.target as HTMLSelectElement).value)
                                if (!isNaN(value)) {
                                    setSelected(value)
                                }
                            }}
                            value={selected}
                        >
                            <option value={0}>
                                {__('No reference', 'shoplic-integration-for-naver-map')}
                            </option>
                            <optgroup label={__('Map', 'shoplic-integration-for-naver-map')}>
                                {maps.length > 0 ?
                                    maps.map((map) => (
                                        <option key={map.post_id} value={map.post_id}>
                                            {'private' === map.post_status && __('Private - ', 'shoplic-integration-for-naver-map')}
                                            [#{map.post_id}] {map.title}
                                            {' - '}
                                            {sprintf(
                                                // Translators: 기록된 위치의 갯수.
                                                _n('%d location', '%d locations', map.locations.length, 'shoplic-integration-for-naver-map'),
                                                map.locations.length,
                                            )}
                                        </option>
                                    )) :
                                    <option disabled={true} value="">
                                        {__('No created maps.', 'shoplic-integration-for-naver-map')}
                                    </option>}
                            </optgroup>
                            <optgroup label={__('Location', 'shoplic-integration-for-naver-map')}>
                                {Object.keys(locations).length > 0 ?
                                    Object.values(locations).map((location) => (
                                        <option key={location.post_id} value={location.post_id}>
                                            [#{location.post_id}] {location.title} - {location.address}
                                        </option>
                                    )) :
                                    <option disabled={true} value="">
                                        {__('No selected locations.', 'shoplic-integration-for-naver-map')}
                                    </option>
                                }
                            </optgroup>
                        </select>
                    </Field>
                </Row>
            </FormTable>
            {/* Nonce is here! */}
            <Nonce
                id="nm-ref-nonce"
                name="nm_ref_nonce"
                defaultValue={nonce}
            />
        </>
    )
}
