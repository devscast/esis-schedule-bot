import jquery from 'jquery'
import axios from '../axios'
import {createButtonLoader, removeButtonLoader} from "./dom"

export default class Modal {

    init = (buttons) => buttons.forEach(button => this.handleSubmit(button))

    handleSubmit = (button) => {
        const url = button.getAttribute('data-url')
        const $modal = $(button.getAttribute('data-target'))
        const $modelContent = $modal.find('.modal-body')

        $modal.on('shown.bs.modal', async () => {
            const response = await axios.get(url)
            $modelContent.html(response.data.html)
            handleSubmitRequest($modelContent, $modal, url)
        })

        const handleSubmitRequest = ($modelContent, $modal, url) => {
            const form = $modelContent.find('form')[0]
            const submitButton = $modelContent.find('button[type="submit"]')[0]

            submitButton.addEventListener('click', async e => {
                try {
                    e.preventDefault()
                    createButtonLoader(submitButton)
                    const response = await axios.post(url, new FormData(form))

                    if (response.status === 201 || response.status === 202) {
                        $modal.modal('hide')
                        $modal.modal('dispose')
                        window.location.reload()
                    }

                    $modal.modal('handleUpdate')
                    $modelContent.html(response.data.html)

                    handleSubmitRequest($modelContent)
                } catch (e) {
                    console.log({e})
                } finally {
                    removeButtonLoader(submitButton, 'envoyer')
                }
            })
        }
    }
}
