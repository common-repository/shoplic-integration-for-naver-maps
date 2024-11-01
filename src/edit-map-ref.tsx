// Vite.
import 'vite/modulepreload-polyfill'
// Library.
import {StrictMode} from 'react'
import {createRoot} from 'react-dom/client'
// Src.
import EditMapRef from './components/EditMapRef.tsx'
import {l10nHelper} from './libs/helper.ts'

const {
    data,
    nonce,
    post_id,
} = nmEditMapRef

console.log('nmEditMapRef', nmEditMapRef)

const root = document.getElementById('nm-edit-map-ref')
if (root) {
    l10nHelper('shoplic-integration-for-naver-map')
    createRoot(root).render(
        <StrictMode>
            <EditMapRef
                data={data}
                nonce={nonce}
                postId={post_id}
            />
        </StrictMode>
    )
} else {
    console.error('#nm-edit-map-ref not found!')
}
