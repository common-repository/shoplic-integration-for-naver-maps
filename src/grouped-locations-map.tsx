// Vite.
import 'vite/modulepreload-polyfill'
// Library.
import {StrictMode} from 'react'
import {createRoot} from 'react-dom/client'
// Src.
import GroupedLocationsMap from './components/GroupedLocationsMap.tsx'
import {l10nHelper} from './libs/helper.ts'
// Types
import {GroupedLocationMapObjectValue} from './types'

l10nHelper('shoplic-integration-for-naver-map')
document.querySelectorAll<HTMLDivElement>(
    '[data-shoplic-naver-map-app-root="true"][data-map_type="grouped-locations-map"]',
).forEach((appRoot: HTMLDivElement) => {
    let objectName: string | null = null
    if ('object_name_postfix' in appRoot.dataset) {
        objectName = 'groupedLocationsMap_' + appRoot.dataset.object_name_postfix
        if (objectName in window) {
            const objectValue = window[objectName] as GroupedLocationMapObjectValue
            console.log(objectName, objectValue)
            createRoot(appRoot).render(
                <StrictMode>
                    <GroupedLocationsMap {...objectValue}/>
                </StrictMode>
            )
        }
    }
})
