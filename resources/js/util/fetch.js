const status = (response) => {
  if (response.ok) {
    return response
  } else {
    const error = new Error(response.statusText || response.status)
    error.response = response
    throw error
  }
}

const headers = (options) => {
  options = options || {}
  options.headers = options.headers || {}
  options.headers['X-Requested-With'] = 'XMLHttpRequest'
  return options
}

const credentials = (options) => {
  if (options == null) {
    options = {}
  }
  if (options.credentials == null) {
    options.credentials = 'same-origin'
  }
  return options
}

const json = (response) => {
  return response.json()
}

const fetchJson = (url, options) => {
  options = headers(credentials(options))
  options.headers.Accept = 'application/json'
  return fetch(url, options).then(status).then(json)
}

export default fetchJson
