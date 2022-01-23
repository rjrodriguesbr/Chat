import axios from 'axios'
import {createStore} from 'vuex'
import createPersistedState from 'vuex-persistedstate'

const store = createStore({
  state: {
    me: {}
  },
  mutations: {
    setUserState: (state, value) => state.me = value
  },
  actions: {
    userStateAction: ({commit}) => {
      axios.get('api/me').then(response => {
        const userResponse = response.data.me
        commit('setUserState', userResponse)
      })
    }
  },
  plugins: [ createPersistedState() ]
})

export default store
