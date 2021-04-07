import axios from '../axios'
import {createButtonLoader, removeButtonLoader, removeFadeOut} from "./dom"
import {confirmation, toast} from "./alert";

export default class DeleteButton {
    constructor(buttons) {
        buttons.forEach(button => {
            const [url, _token, content] = [
                button.getAttribute('data-url'),
                button.getAttribute('data-token'),
                button.innerHTML
            ];

            button.addEventListener('click', async () => {
                await confirmation("Supprimer", async () => {
                    try {
                        createButtonLoader(button);
                        const response = await axios.delete(url, {data: {_token}});
                        response.status === 202 ?
                            removeFadeOut(button.closest(button.getAttribute('data-target'))) :
                            removeButtonLoader(button, content)
                        await toast('success', 'Suppression effectuée avec succès !')
                    } catch (e) {
                        console.error({e})
                        removeButtonLoader(button, content)
                        await toast('error', 'Désolé une erreur s\'est produite !')
                    }
                })
            })
        })
    }
}
