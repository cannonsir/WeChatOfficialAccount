import axios from 'axios'
import config from '../config'
import { Message } from 'element-ui'

const httpRequest = axios.create({
  timeout: 5000,
  baseURL: config.apiUrl
})

httpRequest.interceptors.request.use(
  config => {
    return config
  },
  error => {
    return Promise.reject(error)
  }
)

export function setHttpToken(token) {
  httpRequest.defaults.headers.common.Authorization = `Bearer ${token}`
}

httpRequest.interceptors.response.use(
  response => {
    let data = response
    // 额外处理
    if (data.data) {
    // 追加网络请求
      data = response.data
      if (data) {
        data.status = response.status
      } else {
        data = { status: response.status }
      }
    }
    console.log(data, 'init response')
    return data
  },
  error => {
    if (error.response) {
      let message = error.response.data.message ? error.response.data.message : error.response.statusText
      let dangerouslyUseHTMLString = false

      if (error.response.status === 422 && error.response.data.hasOwnProperty('errors')) {
        message += '<br>'
        for (const key in error.response.data.errors) {
          const items = error.response.data.errors[key]
          if (typeof items === 'string') {
            message += `${items} <br>`
          } else {
            error.response.data.errors[key].forEach(item => {
              message += `${item} <br>`
            })
          }
        }
        dangerouslyUseHTMLString = true
      }

      Message({
        dangerouslyUseHTMLString,
        message: message,
        type: 'error'
      })
    }
    return Promise.reject(error)
  }
)

export default httpRequest
