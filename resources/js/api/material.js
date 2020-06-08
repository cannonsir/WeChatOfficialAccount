import http from '../libs/http'

export const index = (accountId, params) => http.get(`admin-api/WeChatOfficialAccount/accounts/${accountId}/materials`, { params })

export const store = (accountId, data) => http.post(`admin-api/WeChatOfficialAccount/accounts/${accountId}/materials`, data)

export const destroy = (accountId, materialId) => http.delete(`admin-api/WeChatOfficialAccount/accounts/${accountId}/materials/${materialId}`)
