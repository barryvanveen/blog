import fetchJson from '../util/fetch'

const initCsrfTokenRefresher = async () => {
  const forms = document.querySelectorAll('form')
  const token = await getFreshToken()
    .catch(error => {
      console.error('There was an error fetching a csrf token', error)
    })

  forms.forEach(form => {
    const tokenField = form.querySelector('[name="_token"]')

    if (!tokenField) {
      return
    }

    tokenField.value = token
    tokenField.dataset.filled = 'true'
  })
}

const getFreshToken = async () => {
  return await fetchJson('/csrf-token')
    .then((data) => data.token)
}

const csrfTokenRefresher = () => {
  initCsrfTokenRefresher()
}

export default csrfTokenRefresher
