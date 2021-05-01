import axios from '../axios'
import {createButtonLoader, removeButtonLoader} from "./dom";
import {confirmation, toast} from "./alert";

export default class Command {
    constructor(buttons) {
        buttons.forEach(button => {
            button.addEventListener('click', async e => {
                await confirmation('exécuter', () => {
                    const target = e.target
                    createButtonLoader(target, 'exécution...')
                    axios.post(target.getAttribute('data-url'))
                        .then(async response => {
                            if (response.status === 202) {
                                await toast('success', response.data.message)
                            }
                        })
                        .catch(async error => await toast('error', error.response.data.message))
                        .finally(() => removeButtonLoader(target, 'exécuter'))
                })
            });
        });
    }
}
