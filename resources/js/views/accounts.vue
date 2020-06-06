<template>
  <div class="main">
    <div class="wrap">
      <div class="icons">
        <div class="item">
          <div class="content add" @click="addVisible = true"><i class="el-icon-plus">添加</i></div>
        </div>

        <div v-for="account in accounts" :key="account.id" class="item">
          <div class="content icon" @click="handleSelected(account)">
            <div class="avatar">
              <el-avatar shape="square" :src="account.avatar" />
            </div>
            <div class="title">{{ account.name || 'untitled' }}</div>
            <div v-if="account.type" class="desc">{{ account.type }}</div>
          </div>
        </div>

        <div v-if="!inLastPage" class="item">
          <div class="content add" @click="loadMore"><i class="el-icon-more-outline" /></div>
        </div>
      </div>
    </div>

    <el-drawer
      title="绑定公众号"
      :visible.sync="addVisible"
      size="40%"
      class="drawer"
    >
      <div class="add-form">
        <account-sheet ref="accountSheet" @submit="handleAddAccount" />
      </div>
    </el-drawer>
  </div>
</template>

<script>
import AccountSheet from './components/AccountSheet'
import { store, index } from '../api/account'

export default {
  name: 'Accounts',
  components: { AccountSheet },
  data() {
    return {
      accounts: [],
      addVisible: false,
      page: {
        page: 1,
        pageSize: 30,
        total: 0
      }
    }
  },
  computed: {
    inLastPage() {
      return this.page.page * this.page.pageSize >= this.page.total
    }
  },
  created() {
    this.fetchAccounts()
  },
  methods: {
    async fetchAccounts() {
      const { data, meta } = await index(this.page)
      this.accounts = this.page.page === 1 ? data : this.accounts.concat(data)
      this.page.total = meta.total
    },
    handleSelected(account) {
      this.$router.push({ name: 'WeChatOfficialAccount.index', params: { id: String(account.id) }})
    },
    loadMore() {
      ++this.page.page
      this.fetchAccounts()
    },
    async handleAddAccount(data) {
      try {
        await store(data)
        await this.fetchAccounts()
        this.$refs.accountSheet.resetForm()
        this.addVisible = false
        this.$message.success('添加成功')
      } catch (e) {
        console.error(e)
        this.$message.error('操作失败,请稍后再试')
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.main {
  height: calc(100vh - 55px - 100px);
  .wrap {
    margin: auto;
    .icons {
      display: flex;
      align-items: center;
      flex-shrink: 0;
      flex-wrap: wrap;
      .item {
        // 正方形
        width: 12.8%;
        // padding或者margin是已宽度为单位进行百分比取值的
        padding-bottom: 12.8%;
        margin: .7%;
        height: 0;
        cursor: pointer;
        position: relative;
        &:hover {
          box-shadow: 0 2px 8px rgba(0,0,0,.09)
        }
        .content {
          // 通过决定定位来撑起宽高
          position: absolute;
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
          top: 0;
          left: 0;
          height: 100%;
          width: 100%;
          overflow: hidden;
          border-radius: 2px;
        }
        .icon {
          border: 1px solid #e8e8e8;
          &:hover {
            border-color: rgba(0,0,0,.09);
          }
          .avatar {
            width: 70%;
            height: 70%;
            overflow: hidden;
            margin-bottom: 4%;
            display: flex;
            justify-content: center;
            align-items: center;
            .el-avatar {
              width:100%;
              height:100%;
              background: none;
            }
          }
          .title {
            font-size: 1.2rem;
            &:hover {
              color: #40a9ff;
            }
          }
          .desc {
            position: absolute;
            bottom: 0;
            font-size: .6rem;
            color: rgba(0,0,0,.45)
          }
        }
        .add {
          border: 1px dashed #d9d9d9;
          color: rgba(0,0,0,.45);
          &:hover {
            color: #40a9ff;
            border-color: #40a9ff;
          }
        }
      }
    }
  }
}
.add-form {
  padding: 0 15px !important;
}
</style>
