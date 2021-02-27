const status = (response) => {
  if (response.ok) {
    return response
  } else {
    const error = new Error(response.statusText || response.status)
    error.response = response
    throw error
  }
}

const get = (options) => {
  options = options || {}
  options.method = 'GET'
  return options
}

const post = (options) => {
  options = options || {}
  options.method = 'POST'
  return options
}

const headers = (options) => {
  options = options || {}
  options.headers = options.headers || {}
  options.headers.Accept = 'application/json'
  options.headers['Content-Type'] = 'application/json'
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

const body = (options, data) => {
  options = options || {}
  options.body = JSON.stringify(data)
  return options
}

const json = (response) => {
  return response.json()
}

const fetchJson = (url, options) => {
  options = get(headers(credentials(options)))
  return fetch(url, options).then(status).then(json)
}

const postJson = (url, data, options) => {
  options = post(headers(credentials(body(options, data))))
  return fetch(url, options).then(status).then(json)
}

export {
  fetchJson,
  postJson
}
