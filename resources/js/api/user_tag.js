import http from '../libs/http'

export const index = (id, params = {}) => http.get(`admin-api/WeChatOfficialAccount/accounts/${id}/user_tags`, { params })
