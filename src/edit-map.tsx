// Vite.
import 'vite/modulepreload-polyfill'
// Library.
import {StrictMode} from 'react'
import {createRoot} from 'react-dom/client'
// Src.
import EditMap from './components/EditMap.tsx'
import {l10nHelper} from './libs/helper.ts'

const {
    data,
    nonce,
    post_id,
} = nmEditMap

console.log('nmEditMap', nmEditMap)

const root = document.getElementById('nm-edit-map')
if (root) {
    l10nHelper('shoplic-integration-for-naver-map')
    createRoot(root).render(
        <StrictMode>
            <EditMap data={data} nonce={nonce} postId={parseInt(post_id)}/>
        </StrictMode>
    )
} else {
    console.error('#nm-edit-map not found!')
}