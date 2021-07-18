import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
import repositories from './repositories';

export default new Vuex.Store({
    state: {
        users: [],
        legalCases: [],
        staticData:{
            judicialStatusList: [],
            subjectList: [],
            administrativeStatusList: [],
            locationList: [],
        },
        locationStaticData: {'999': 'Archivo'}
    },
    getters: {
        users: state => state.users,
        legalCases: state => userID => state.legalCases[userID]
        /*students: state => state.students.map(s => ({
            ...s, fullName: s.firstName + ' ' + s.lastName
        })),
        findStudent: state => id => state.students.find(s => s.id == id),
        isLoaded: state => !!state.students.length*/
    },
    mutations: {
        setJudicialStatusList(state, data){
            state.staticData.judicialStatusList = data;
        },
        setSubjectList(state, data){
            state.staticData.subjectList = data;
        },
        setAdministrativeStatusList(state, data){
            state.staticData.administrativeStatusList = data;
        },
        setLocationList(state, data){
            state.staticData.locationList = data;
        },
        setClients(state, data){
            state.users = data;
        },
        setUsers(state, data){
            state.users = data;
        },
        setLegalCasesBy(state, {data, userID}){
            //console.log(state);
            //console.log(response);
            //console.log(userID);
            state.legalCases[userID] = data;
        }
        /*setStudents(state, students) {
            state.students = students;
        },
        addStudent(state, student) {
            state.students.push(student);
        },
        editStudent(state, student) {
            const index = state.students.findIndex(s => s.id == student.id);
            Vue.set(state.students, index, student);
        }*/
    },
    actions: {
        /*async getStudents(context) {
            try {
                const students = (await axios.get('http://localhost:3000/students')).data;
                context.commit('setStudents', students);   
            } catch (error) {
                context.commit('showError', error);
            }

        },
        async createStudent(context, names ) {
            const student = (await axios.post("http://localhost:3000/students", names )).data;
            context.commit('addStudent', student);
        },
        async editStudent(context, {id, names}) {
            const student = (await axios.put(`http://localhost:3000/students/${id}`, names)).data;
            context.commit('editStudent', student);
        }*/
        async getJudicialStatusList(context) {
            try {
                const data = await repositories.getJudicialStatusList();
                const response = data.response;
                context.commit('setJudicialStatusList', response);   
            } catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getSubjectList(context) {
            try {
                const data = await repositories.getSubjectList();
                const response = data.response;
                context.commit('setSubjectList', response);   
            } catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAdministrativeStatusList(context){
            try {
                const data = await repositories.getAdministrativeStatusList();
                const response = data.response;
                context.commit('setAdministrativeStatusList', response);   
            } catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getLocationListData(context){
            try {
                const data = await repositories.getClientBy('roleID !=', '0');
                const response = data.response;

                response.forEach(item => {
                    item.location = item.name + ' ' + item.lastName1 + ' ' + item.lastName2;  
                });
                response.push({'location': context.state.locationStaticData['999'], 'id': '999'});
                context.commit('setLocationList', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAllClients(context){
            try {
                const data = await repositories.getAllClients();
                const response = data.response;
                context.commit('setClients', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAllUsers(context){
            try {
                const data = await repositories.getAllUsers();
                const response = data.response;
                context.commit('setUsers', response);  
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getClientBy(context, {service, searchBy, value, callback}){
            try {
                const data = await repositories[service](searchBy, value);
                const response = data.response;
                context.commit('setClients', response);
                
                if(callback && response.length){
                    callback(response);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getLegalCasesBy(context, {searchBy, value, userID, callback}){
            try {
                const data = await repositories.getLegalCasesBy(searchBy, value);
                const response = data.response;

                response.forEach(item => {
                    item.location = item.locationID != '999' ? (item.name ? item.name : '') + ' ' + (item.lastName1 ? item.lastName1 : '') + ' ' + (item.lastName2 ? item.lastName2 : '') : context.state.locationStaticData['999'];
                });

                context.commit('setLegalCasesBy', {data:response, userID});

                if(callback && response.length){
                    callback(response);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        }
    },
    modules: {
        //error: errorSystem
    }
})