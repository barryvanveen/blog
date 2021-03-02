import { postJson } from '../util/fetch'

const formErrorClass = 'js-error'

const initCommentSubmitter = async () => {
  const forms = document.querySelectorAll('form[name="comment"]')

  forms.forEach(form => {
    form.addEventListener('submit', submitComment)
  })
}

const submitComment = async (event) => {
  event.preventDefault()

  const form = event.target
  const formData = getFormData(form)

  clearFormErrors(form)

  postJson(form.action, formData)
    .then(() => {
      redirectToLatestComment()
    })
    .catch((error) => {
      error.response.json()
        .then(data => showFormErrors(form, data))
    })
}

const clearFormErrors = (form) => {
  const errors = form.querySelectorAll(`.${formErrorClass}`)
  errors.forEach((error) => {
    error.remove()
  })
}

const getFormData = (form) => {
  const formData = new FormData(form)

  const data = {}
  formData.forEach((value, key) => {
    data[key] = value
  })

  return data
}

const redirectToLatestComment = () => {
  window.location.reload()
}

const showFormErrors = (form, errors) => {
  Object.entries(errors).forEach(([key, value]) => {
    const input = form.querySelector('[name=' + key + ']')
    if (!input) {
      return
    }
    const error = createErrorElement(value)
    insertAfter(error, input)
  })

  if (!errors.error) {
    return
  }

  const formError = createErrorElement(errors.error)
  const submitButton = form.querySelector('input[type="submit"]')
  insertAfter(formError, submitButton)
  console.error(errors.error)
}

const createErrorElement = (value) => {
  const error = document.createElement('p')
  error.className = `text-red-500 italic ${formErrorClass}`
  error.textContent = value
  return error
}

const insertAfter = (el, after) => {
  after.parentNode.insertBefore(el, after.nextSibling)
}

const commentSubmitter = () => {
  initCommentSubmitter()
}

export default commentSubmitter
