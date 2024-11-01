import {joinClassNames} from '../../libs/helper.ts'
import {HTMLAttributes, PropsWithChildren} from 'react'

type DescProps = HTMLAttributes<HTMLParagraphElement> & PropsWithChildren

export default function Description(props: DescProps) {
    return <p className={`${joinClassNames('description', props.className)}`}>{props.children}</p>
}
