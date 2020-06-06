import http from '../libs/http'

export const index = (id, params = {}) => http.get(`admin-api/WeChatOfficialAccount/accounts/${id}/users`, { params })

export const sync = (id) => http.put(`admin-api/WeChatOfficialAccount/accounts/${id}/users`)
