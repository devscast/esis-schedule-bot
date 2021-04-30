import {toast} from './alert'

export default class Flash {
    constructor(elements) {
        elements.forEach(async (e) => {
            await toast(
                e.getAttribute('data-flash-type'),
                e.getAttribute('data-flash-message')
            )
        })
    }
}
