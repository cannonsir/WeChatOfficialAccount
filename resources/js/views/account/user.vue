<template>
  <div>
    <avue-crud
      :data="users"
      :option="option"
      :page="page"
      :table-loading="tableLoading"
      @selection-change="handleSelectionChange"
      @search-change="handleSearchChange"
      @current-change="handleCurrentPageChange"
      @size-change="handleSizeChange"
    >
      <template v-slot:tagid_listSearch="scope">
        <el-select v-model="scope.row.tagid_list" clearable filterable multiple placeholder="请选择">
          <el-option
            v-for="tag in tags"
            :key="tag.id"
            :label="tag.name"
            :value="tag.id"
          />
        </el-select>
      </template>

      <template slot="menuLeft">
        <el-button type="primary" size="small" @click="handleSyncUsers">同步用户</el-button>
      </template>

      <template slot="tip">
        <el-button type="text" size="small" @click="setTag">
          新增标签
        </el-button>
      </template>

      <template v-slot:headimgurl="{ row }">
        <el-avatar :src="row.headimgurl" />
      </template>
      <template v-slot:headimgurlForm="{ row }">
        <img :src="row.headimgurl" class="avatar">
      </template>

      <template v-slot:menu="{ row }">
        <el-button size="small" type="text">发送消息</el-button>
      </template>
    </avue-crud>
  </div>
</template>

<script>
import { index as UserIndex, sync as syncUsers } from '../../api/user'
import { index as UserTagIndex } from '../../api/user_tag'
import _ from 'lodash'
import { denders, sources } from '../../enums/user'

export default {
  name: 'User',
  props: {
    account: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      users: [],
      page: {
        page: 1,
        pageSize: 10,
        total: 0
      },
      tags: [],
      selectedRows: [],
      tableLoading: false,
      search: {}
    }
  },
  computed: {
    option() {
      return {
        title: '公众号粉丝',
        page: false,
        align: 'center',
        menuAlign: 'center',
        editBtn: false,
        delBtn: false,
        addBtn: false,
        viewBtn: true,
        selection: true,
        searchMenuSpan: 3,
        labelWidth: 120,
        column: [
          {
            label: '头像',
            prop: 'headimgurl',
            slot: true,
            formslot: true
          },
          {
            label: '昵称',
            prop: 'nickname'
          },
          {
            label: '性别',
            prop: 'sex',
            type: 'select',
            hide: true,
            dicData: denders
          },
          {
            label: '是否关注',
            prop: 'subscribe',
            type: 'switch',
            hide: true
          },
          {
            label: 'openid',
            prop: 'openid',
            hide: true
          },
          {
            label: 'unionid',
            prop: 'unionid',
            hide: true
          },
          {
            label: '标签',
            prop: 'tagid_list',
            type: 'checkbox',
            searchslot: true,
            search: true,
            dicData: this.tags.map(({ id, name }) => ({ value: id, label: name }))
          },
          {
            label: '语言',
            prop: 'language',
            hide: true
          },
          {
            label: '国家',
            prop: 'country',
            hide: true
          },
          {
            label: '备注',
            prop: 'remark',
            hide: true
          },
          {
            label: '省份',
            prop: 'privice',
            hide: true
          },
          {
            label: '关注时间',
            prop: 'subscribe_time',
            type: 'date',
            format: 'yyyy-MM-dd hh:mm:ss',
            valueFormat: 'yyyy-MM-dd hh:mm:ss'
          },
          {
            label: '城市',
            prop: 'city',
            hide: true
          },
          {
            label: '渠道来源',
            prop: 'subscribe_scene',
            type: 'select',
            dicData: sources,
            hide: true
          },
          {
            label: '最后同步时间',
            prop: 'last_sync_at',
            type: 'date',
            format: 'yyyy-MM-dd hh:mm:ss',
            valueFormat: 'yyyy-MM-dd hh:mm:ss'
          }
        ]
      }
    },
    fetchUserParams() {
      const params = {
        ...this.page
      }

      const filter = _.cloneDeep(this.search)

      for (const key in filter) {
        if (Array.isArray(filter[key])) {
          filter[key] = {
            operation: 'in',
            value: filter[key]
          }
        }
      }

      params.filter = JSON.stringify(filter)

      return params
    }
  },
  created() {
    this.fetchUsers()
    this.fetchAllTags()
  },
  methods: {
    async handleSyncUsers() {
      const loading = this.$loading({ text: '请求微信服务器中，请稍等...' })

      try {
        await syncUsers(this.account.id)
        await this.fetchUsers()
        this.$message.success('同步成功')
      } catch (e) {
        this.$message.error('同步失败')
      }

      loading.close()
    },
    async fetchUsers() {
      this.tableLoading = true

      try {
        const { data, meta } = await UserIndex(this.account.id, this.fetchUserParams)
        this.users = data
        this.page.total = meta.total
      } catch (e) {
        console.error(e)
      }

      this.tableLoading = false
    },
    async fetchAllTags() {
      const res = await UserTagIndex(this.account.id)

      this.tags = res.tags || []
    },
    handleSelectionChange(data) {
      this.selectedRows = data
    },
    setTag(row = null) {
      if (row) {
        console.log(row.openid)
        return
      }

      const openids = this.selectedRows.map(item => item.openid)
      console.log(openids)
    },
    async handleSearchChange(params, done) {
      this.search = params
      await this.fetchUsers()
      done()
    },
    handleCurrentPageChange(page) {
      this.page.page = page
      this.fetchUsers()
    },
    handleSizeChange(size) {
      this.pageSize = size
      this.fetchUsers()
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.avatar {
  width: 120px;
  height: 120px;
  display: block;
  border-radius: 5px;
}
</style>
