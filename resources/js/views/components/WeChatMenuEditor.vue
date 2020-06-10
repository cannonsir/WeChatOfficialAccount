<template>
  <div class="warp">
    <mobile-preview ref="mobilePreview" v-model="menu.button" class="preview" @selected-id-change="selected = $event" />
    <div v-if="selectedButton" class="adjust">
      <event-assign ref="eventAssign" v-model="selectedButton" @destroy="handleDestroyButton" />
    </div>

    <pre>{{ menu }}</pre>
  </div>
</template>

<script>
import MobilePreview from './MobilePreview'
import EventAssign from './EventAssign'
import _ from 'lodash'
import { allowButtons } from './DynamicTypeInput'

export default {
  name: 'WeChatMenuEditor',
  components: { EventAssign, MobilePreview },
  props: {
    value: {
      type: Object,
      default: () => {}
    }
  },
  data() {
    return {
      menu: {},
      selected: null
    }
  },
  computed: {
    selectedButtonArrayPath() {
      const id = this.selected

      for (const index in this.menu.button) {
        const ref = this.menu.button[index]

        if (ref.id === id) return [index]

        const findSubButtonIndex = _.findIndex(ref.sub_button || [], { id })

        if (findSubButtonIndex !== -1) return [index, findSubButtonIndex]
      }

      return []
    },
    selectedButtonStringPath() {
      return this.selectedButtonArrayPath.reduce((res, next) => `${res}${res === '' ? '' : '.sub_button'}[${next}]`, '')
    },
    selectedButton: {
      get() {
        return _.get(this.menu.button, this.selectedButtonStringPath) || null
      },
      set(button) {
        const arr = this.selectedButtonArrayPath

        if (arr.length === 1) {
          this.$set(this.menu.button, arr[0], button)
        }

        if (arr.length === 2) {
          this.$set(this.menu.button[arr[0]].sub_button, arr[1], button)
        }
      }
    }
  },
  watch: {
    value: {
      immediate: true,
      deep: true,
      handler(value) {
        const copy = _.cloneDeep(value)
        copy.button = this.deepButtonsGenerateId(copy.button || [])
        this.menu = copy
      }
    },
    menu: {
      immediate: true,
      deep: true,
      handler(menu) {
        const copy = _.cloneDeep(menu)
        copy.button = this.deepButtonsDeleteId(copy.button || [])
        this.$emit('change', copy)
      }
    }
  },
  methods: {
    deepButtonsGenerateId(buttons) {
      return buttons.map(button => {
        button.id = button.id || Math.random().toString(36).slice(-8)
        if (button.sub_button) {
          button.sub_button = this.deepButtonsGenerateId(button.sub_button)
        }
        return button
      })
    },
    deepButtonsDeleteId(buttons) {
      return buttons.map(button => {
        delete button.id
        if (button.sub_button) {
          button.sub_button = this.deepButtonsDeleteId(button.sub_button)
        }
        return button
      })
    },
    handleDestroyButton() {
      const arr = this.selectedButtonArrayPath

      if (arr.length < 1 || arr.length > 2) return

      if (arr.length === 1) {
        this.$delete(this.menu.button, arr[0])
      } else {
        this.$delete(this.menu.button, arr[1])
      }
    },
    formatTransform(menu) {
      const button = menu.button

      const cleanTrashFields = button => {
        for (const key in button) {
          [].includes(key) || delete button[key]
        }
        return button
      }

      for (const key in button) {
        button[key] = cleanTrashFields(button[key])
      }

      return button
    },
    validate() {
      const allowTypes = allowButtons.map(item => (item.value))
      const formValidate = () => this.$refs.eventAssign && this.$refs.eventAssign.formValidate()
      const clearFormValidate = () => this.$refs.eventAssign && this.$refs.eventAssign.clearValidate()

      if (this.$refs.eventAssign && !formValidate()) return false

      const deepValidate = (buttons) => {
        for (const button of buttons) {
          if (button.sub_button) {
            if (deepValidate(button.sub_button)) {
              continue
            } else {
              return false
            }
          } else {
            if (!button.type || !allowTypes.includes(button.type)) {
              this.$refs.mobilePreview.toggleSelected(button.id, true)
              // clearFormValidate()
              formValidate()
              return false
            }
          }
        }

        return true
      }

      return deepValidate(this.menu.button)
    }
  }
}
</script>

<style lang="scss" scoped>
.warp {
  // TODO
  height: 580px;
  width: 100%;
  display: flex;
  .preview {
    flex-shrink: 0;
  }
  .adjust {
    margin-left: 20px;
    width: 100%;
  }
}
</style>
