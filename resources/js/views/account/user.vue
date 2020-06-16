<template>
  <div>
    <el-container>
      <el-aside width="200px" v-loading="loadingTags">
        <el-button type="primary" @click="handleCreateTag" style="width: 100%">新增标签</el-button>
        <el-tree
          :data="allTags"
          :props="{ label: 'name' }"
          node-key="id"
          :current-node-key="currentSelectedTag"
          @node-click="handleTreeNodeClick"
        >
          <div slot-scope="{ node, data }" class="true-node">
            <div>{{ node.label }}({{ data.count }})</div>
            <div class="operation" v-if="data.id !== ''">
              <el-button
                type="text"
                size="mini"
                @click.stop="handleUpdateTag(data)">
                <i class="el-icon-edit" />
              </el-button>
              <el-button
                type="text"
                size="mini"
                @click.stop="handleRemoveTag(data)">
                <i class="el-icon-delete" />
              </el-button>
            </div>
          </div>
        </el-tree>
      </el-aside>
      <el-main>
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
          <template slot="menuLeft">
            <el-button type="primary" size="small" @click="handleSyncUsers">同步用户</el-button>
          </template>

          <template slot="tip">
            <el-popover
              placement="bottom"
              width="200"
              trigger="click"
              @show="tagIdSnap = []"
            >

              <el-checkbox-group v-model="tagIdSnap">
                <el-checkbox v-for="tag in tags" :key="tag.id" :label="tag.id">{{ tag.name }}</el-checkbox>
              </el-checkbox-group>

              <el-button @click="handleAttachTags" style="width: 100%">确认</el-button>

              <el-button slot="reference" type="text" size="small" style="margin-left: 10px;">批量新增标签</el-button>
            </el-popover>

          </template>

          <template v-slot:headimgurl="{ row }">
            <el-avatar :src="row.headimgurl" />
          </template>
          <template v-slot:headimgurlForm="{ row }">
            <img :src="row.headimgurl" class="avatar">
          </template>
          <template v-slot:subscribe="{ row }">
            {{ row.subscribe ? '是' : '否' }}
          </template>

          <template v-slot:tagid_list="{ row }">
            <el-tag style="cursor: default" v-for="tag in row.tagid_list" :key="tag">{{ getTagName(tag) }}</el-tag>
          </template>

          <template v-slot:menu="{ row }">
            <el-popover
              placement="bottom"
              width="200"
              trigger="click"
              @show="tagIdSnap = row.tagid_list"
            >
              <el-checkbox-group v-model="tagIdSnap">
                <el-checkbox v-for="tag in tags" :key="tag.id" :label="tag.id">{{ tag.name }}</el-checkbox>
              </el-checkbox-group>

              <el-button @click="handleSyncTags(row.openid)" style="width: 100%">更新</el-button>

              <el-button slot="reference" icon="el-icon-thumb" size="small" type="text">标签管理</el-button>
            </el-popover>

            <el-button icon="el-icon-chat-round" size="small" type="text">发送消息</el-button>
          </template>
        </avue-crud>
      </el-main>
    </el-container>
  </div>
</template>

<script>
import { index as UserIndex, sync as syncUsers } from '../../api/user'
import {
  index as UserTagIndex,
  store as UserTagStore,
  update as UserTagUpdate,
  destroy as UserTagDestroy,
  attachTagForUsers,
  syncTagForUsers
} from '../../api/user_tag'
import _ from 'lodash'
import { denders, sources } from '../../enums/user'

export default {
  name: 'User',
  inject: ['account'],
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
      search: {},
      currentSelectedTag: '',
      loadingTags: false,
      userCount: 0,
      tagIdSnap: []
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
            slot: true
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
            slot: true,
            overHidden: true,
            searchslot: true,
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

      // 标签筛选
      this.currentSelectedTag === ''
        ? delete filter.tagid_list
        : filter.tagid_list = {
          operation: 'jsoncontains',
          value: this.currentSelectedTag
        }

      params.filter = JSON.stringify(filter)

      return params
    },
    allTags() {
      return [
        {
          id: '',
          name: '所有用户',
          count: this.userCount
        },
        ...this.tags
      ]
    }
  },
  watch: {
    currentSelectedTag() {
      this.page.page = 1
      this.fetchUsers()
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
        if (this.fetchUserParams.filter === '{}') {
          this.userCount = meta.total
        }
      } catch (e) {
        console.error(e)
      }

      this.tableLoading = false
    },
    async fetchAllTags() {
      try {
        this.loadingTags = true

        const res = await UserTagIndex(this.account.id)

        this.tags = res.tags || []
      } catch (e) {
        console.error(e)
        this.$message.error('标签数据拉取失败')
      } finally {
        this.loadingTags = false
      }
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
    },
    getTagName(id) {
      return (_.find(this.tags, { id }) || {}).name || '-'
    },
    handleTreeNodeClick(tag) {
      this.currentSelectedTag = tag.id
    },
    async handleCreateTag() {
      let name
      try {
        const res = await this.$prompt('请填写标签名称', '创建标签', {
          confirmButtonText: '新增',
          cancelButtonText: '取消'
        })

        name = res.value
      } catch {
        return
      }

      try {
        this.loadingTags = true
        await UserTagStore(this.account.id, { name })
        this.$message.success('创建成功')
        await this.fetchAllTags()
      } catch (e) {
        console.error(e)
        this.$message.error('创建失败')
      } finally {
        this.loadingTags = false
      }
    },
    async handleUpdateTag(tag) {
      let name
      try {
        const res = await this.$prompt('请填写标签名称', '更新标签', {
          confirmButtonText: '更新',
          cancelButtonText: '取消',
          inputValue: tag.name
        })

        name = res.value
      } catch {
        return
      }

      try {
        this.loadingTags = true
        await UserTagUpdate(this.account.id, tag.id, { name })
        this.$message.success('更新成功')
        await this.fetchAllTags()
      } catch (e) {
        console.error(e)
        this.$message.error('更新失败')
      } finally {
        this.loadingTags = false
      }
    },
    async handleRemoveTag(tag) {
      try {
        await this.$confirm('此操作将永久删除该标签, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
      } catch {
        return
      }

      try {
        this.loadingTags = true
        await UserTagDestroy(this.account.id, tag.id)
        this.$message.success('删除成功')
        await this.fetchAllTags()
      } catch (e) {
        console.error(e)
        this.$message.error('删除失败')
      } finally {
        this.loadingTags = false
      }

      if (tag.id === this.currentSelectedTag) {
        this.currentSelectedTag = ''
      }
    },
    async handleAttachTags() {
      if (this.selectedRows.length === 0) {
        return this.$message.info('请选中用户')
      }

      if (this.tagIdSnap.length === 0) {
        return this.$message.info('请选中标签')
      }

      for(const row of this.selectedRows) {
        if (row.subscribe === false) {
          this.$message.warning('不再关注的用户无效')
        }
      }

      const openIds = this.selectedRows.map(row => row.openid)

      try {
        this.tableLoading = true
        await attachTagForUsers(this.account.id, { tagIds: this.tagIdSnap, openIds })
        this.$message.success('更新成功')
        this.fetchUsers()
        this.fetchAllTags()
      } catch (e) {
        console.error(e)
        this.$message.error('操作失败')
      } finally {
        this.tableLoading = false
      }
    },
    async handleSyncTags(userOpenId) {
      const loading = this.$loading({ text: '更新中...' })
      try {
        await syncTagForUsers(this.account.id, { tagIds: this.tagIdSnap, openIds: [userOpenId] })
        this.$message.success('更新成功')
        await Promise.all([this.fetchUsers(), this.fetchAllTags()])
      } catch (e) {
        console.error(e)
        this.$message.error('操作失败')
      } finally {
        loading.close()
      }
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

.true-node {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
  .operation {
    display: none;
  }
  &:hover .operation {
    display: block;
  }
}
</style>
