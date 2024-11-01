// Vite.
import 'vite/modulepreload-polyfill'
// Library.
import {StrictMode} from 'react'
import {createRoot} from 'react-dom/client'
// Src.
import EditLocation from './components/EditLocation.tsx'
import {l10nHelper} from './libs/helper.ts'

const {
    data,
    nonce,
    post_id,
} = nmEditLocation

console.log('nmEditLocation', nmEditLocation)

const root = document.getElementById('nm-edit-location')
if (root) {
    l10nHelper('shoplic-integration-for-naver-map')
    createRoot(root).render(
        <StrictMode>
            <EditLocation data={data} nonce={nonce} postId={parseInt(post_id)}/>
        </StrictMode>
    )
} else {
    console.error('#nm-edit-location not found!')
}
