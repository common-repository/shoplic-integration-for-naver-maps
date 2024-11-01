import {DetailedHTMLProps, InputHTMLAttributes} from 'react'

type NonceProps = DetailedHTMLProps<InputHTMLAttributes<HTMLInputElement>, HTMLInputElement>

export default function Nonce(props: NonceProps) {
    return <input type="hidden" {...props} />
}
