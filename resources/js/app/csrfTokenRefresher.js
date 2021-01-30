import fetchJson from '../util/fetch'

const initCsrfTokenRefresher = () => {
  const forms = document.querySelectorAll('form')

  forms.forEach(form => {
    registerSubmitEventListener(form)
  })
}

const registerSubmitEventListener = (el) => {
  el.addEventListener('submit', formSubmit)
}

const setFreshToken = async (el) => {
  const data = await fetchJson('/csrf-token')

  el.value = data.token
}

const formSubmit = async (event) => {
  event.preventDefault()

  const form = event.target
  const tokenField = form.querySelector('[name="_token"]')

  if (!tokenField) {
    return
  }

  await setFreshToken(tokenField)
    .catch(error => {
      console.error('There was an error fetching a csrf token', error)
    })

  form.submit()
}

const csrfTokenRefresher = () => {
  initCsrfTokenRefresher()
}

export default csrfTokenRefresher
