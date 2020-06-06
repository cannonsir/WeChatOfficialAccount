import http from '../libs/http'

export const index = (params = {}) => http.get(`admin-api/WeChatOfficialAccount/accounts`, { params })

export const show = (accountId) => http.get(`admin-api/WeChatOfficialAccount/accounts/${accountId}`)

export const store = (data) => http.post(`admin-api/WeChatOfficialAccount/accounts`, data)

export const update = (accountId, data) => http.patch(`admin-api/WeChatOfficialAccount/accounts/${accountId}`, data)

export const destroy = (accountId) => http.delete(`admin-api/WeChatOfficialAccount/accounts/${accountId}`)
