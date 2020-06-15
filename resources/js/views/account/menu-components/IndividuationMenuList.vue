<template>
  <div>
    <avue-crud v-if="$route.name === 'WeChatOfficialAccount.menu'" :data="menus" :option="option" v-model="obj" :table-loading="loading" @refresh-change="fetchIndividuationMenus()">
      <template slot="menuLeft">
        <el-button icon="el-icon-plus" type="primary" size="small" @click="handleAdd">新增</el-button>
      </template>

      <template v-slot:menu="{ type, size, row }">
        <el-button icon="el-icon-view" :size="size" :type="type" @click="handleView(row)">查看</el-button>
        <el-button icon="el-icon-delete" :size="size" :type="type" @click="handleDelete(row)">删除</el-button>
      </template>
    </avue-crud>

    <router-view />
  </div>
</template>

<script>
import { individuationMenuIndex, destroy } from "../../../api/menu";

export default {
  name: "IndividuationMenuList",
  inject: ['account'],
  data() {
    return {
      loading: false,
      obj:{},
      menus: [],
      option:{
        title:'表格的标题',
        page:false,
        align:'center',
        menuAlign:'center',
        delBtn: false,
        editBtn: false,
        columnBtn: false,
        addBtn: false,
        column:[
          {
            label: '微信标识',
            prop: 'menuid'
          },
          {
            label:'显示对象',
            prop: 'individuation'
          }
        ]
      }
    }
  },
  watch: {
    '$route.name': {
      immediate: true,
      handler: 'fetchIndividuationMenus'
    }
  },
  methods: {
    async fetchIndividuationMenus() {
      try {
        this.loading = true
        this.menus = await individuationMenuIndex(this.account.id)
      } catch (e) {
        this.$message.error('拉取失败')
      } finally {
        this.loading = false
      }
    },
    handleAdd() {
      this.$router.push({ name: 'WeChatOfficialAccount.menu.individuation' })
    },
    handleView(menu) {
      this.$router.push({ name: 'WeChatOfficialAccount.menu.individuation', params: { default: menu, disabled: true }})
    },
    async handleDelete(row) {
      try {
        await this.$confirm('此操作将永久删除该菜单, 是否继续?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        })
      } catch {
        return
      }

      const loading = this.$loading()

      try {
        await destroy(this.account.id, row.menuid)
        this.fetchIndividuationMenus()

        this.$message.success('已删除')
      } catch (e) {
        this.$message.error('操作失败')
        console.error(e)
      } finally {
        loading.close()
      }
    }
  }
}
</script>

<style scoped>

</style>
