import http from '../libs/http'

export const store = (accountId, data) => http.post(`admin-api/WeChatOfficialAccount/accounts/${accountId}/menu`, data)
