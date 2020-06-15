import http from '../libs/http'

export const store = (accountId, data) => http.post(`admin-api/WeChatOfficialAccount/accounts/${accountId}/menu`, data)

export const current = (accountId, params) => http.get(`admin-api/WeChatOfficialAccount/accounts/${accountId}/menu/current`, { params })

export const individuationMenuIndex = (accountId, params) => http.get(`admin-api/WeChatOfficialAccount/accounts/${accountId}/menu/individuation`)

export const destroy = (accountId, menuId) => http.delete(`admin-api/WeChatOfficialAccount/accounts/${accountId}/menu/${menuId}`)
