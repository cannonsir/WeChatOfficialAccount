import http from '../libs/http'

export const index = (accountId, params = {}) => http.get(`admin-api/WeChatOfficialAccount/accounts/${accountId}/user_tags`, { params })

export const store = (accountId, data) => http.post(`admin-api/WeChatOfficialAccount/accounts/${accountId}/user_tags`, data)

export const update = (accountId, tagId, data) => http.patch(`admin-api/WeChatOfficialAccount/accounts/${accountId}/user_tags/${tagId}`, data)

export const destroy = (accountId, tagId) => http.delete(`admin-api/WeChatOfficialAccount/accounts/${accountId}/user_tags/${tagId}`)

export const attachTagForUsers = (accountId, data) => http.post(`admin-api/WeChatOfficialAccount/accounts/${accountId}/user_tags/attach`, data)

export const detachTagForUsers = (accountId, data) => http.post(`admin-api/WeChatOfficialAccount/accounts/${accountId}/user_tags/detach`, data)

export const syncTagForUsers = (accountId, data) => http.post(`admin-api/WeChatOfficialAccount/accounts/${accountId}/user_tags/sync`, data)
