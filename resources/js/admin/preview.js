import debounce from '../util/debounce'
import getVariable from '../util/variables'
import { postJson } from '../util/fetch'

const initPreviews = () => {
  const editors = document.querySelectorAll('[data-editor]')

  editors.forEach(editor => {
    registerInputEventListener(editor)
  })
}

const registerInputEventListener = (el) => {
  const previewEl = document.querySelector(el.dataset.preview)

  if (!previewEl) {
    return
  }

  el.addEventListener('input', debounce(updatePreview, 1000))
  el.dispatchEvent(new Event('input'))
}

const getHtmlFromMarkdown = (markdown) => {
  const data = {
    markdown,
    _token: document.querySelector('input[name="_token"]').value
  }

  const url = getVariable('markdown_to_html_url')

  return postJson(url, data)
}

const setHtmlOnPreviewElement = (html, el) => {
  el.innerHTML = html
}

const setPreviewElementAsFilled = (el) => {
  el.dataset.filled = 'true'
}

const updatePreview = (event) => {
  const markdown = event.target.value
  const el = document.querySelector(event.target.dataset.preview)

  if (!el) {
    return
  }

  delete el.dataset.filled

  getHtmlFromMarkdown(markdown)
    .then(data => setHtmlOnPreviewElement(data.html, el))
    .then(() => setPreviewElementAsFilled(el))
    .catch(err => console.warn('Something went wrong.', err))
}

const preview = () => {
  initPreviews()
}

export default preview
