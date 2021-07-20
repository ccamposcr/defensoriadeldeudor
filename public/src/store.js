import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
import repositories from './repositories';

export default new Vuex.Store({
    state: {
        showLoader: false,
        users: [],
        legalCases: [],
        staticData:{
            judicialStatusList: [],
            subjectList: [],
            administrativeStatusList: [],
            locationList: [],
            appointmentTypeList: []
        },
        locationStaticData: {'999': 'Archivo'},
        isClientInUse: 0,
        clientForm:{
            id:'',
            personalID:'',
            name: '',
            lastName1: '',
            lastName2: '',
            phone: '',
            email: '',
            address: '',
            roleID:'0',
            status: '1',
            phone2: '',
            phone3: '',
            email2: '',
            email3: '',
            job: '',
            inUse: '0'
        },
        isLegalCaseInUse: 0,
        legalCaseForm:{
            id: '',
            internalCode: '',
            subjectID: '',
            userID: '',
            judicialStatusID: '',
            administrativeStatusID: '',
            note: '',
            totalAmount: 0,
            legalCaseID: '',
            locationID: '',
            code: '',
            inUse: '0'
        },
        currentLegalCaseUserId: '',
        paymentDates:{
            legalCaseID: '',
            dates: []
        },
        legalCaseNotes: [],
        legalPaymentDates: [],
        currentUserIdUpdatePassword: '',
        legalCasePaymentDates: [],
        appointmentsDates: [],
        events: [],
        appointmentForm: {
            date: '',
            userID: '',
            internalUserID: '',
            madeByUserID: '',
            filterBy: '',
            clientList: [],
            usersList: [],
            alertColor: '#28a745',
            appointmentTypeID: ''
        },
        appointmentOriginalClientList: [],
        searchClientForm:{
            personalID: '',
            name: '',
            code: '',
            internalCode: '',
            searchBy: 'personalID'
        },
        updatePasswordForm:{
            password: '',
            confirmPassword: ''
        }
    },
    getters: {
        users: state => state.users,
        legalCases: state => userID => state.legalCases[userID],
        isClientInUse: state => state.isClientInUse,
        clientForm: state => state.clientForm,
        isLegalCaseInUse: state => state.isLegalCaseInUse,
        legalCaseForm: state => state.legalCaseForm,
        currentLegalCaseUserId: state => state.currentLegalCaseUserId,
        paymentDates: state => state.paymentDates,
        legalCaseNotes: state => legalCaseID => state.legalCaseNotes[legalCaseID],
        legalPaymentDates: state => legalCaseID => state.legalPaymentDates[legalCaseID],
        currentUserIdUpdatePassword: state => state.currentUserIdUpdatePassword,
        showLoader: state => state.showLoader,
        legalCasePaymentDates: state => state.legalCasePaymentDates,
        appointmentsDates: state => state.appointmentsDates,
        events: state => state.events,
        staticData: state => state.staticData,
        appointmentForm: state => state.appointmentForm,
        appointmentOriginalClientList: state => state.appointmentOriginalClientList,
        searchClientForm: state => state.searchClientForm,
        updatePasswordForm: state => state.updatePasswordForm
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
        setAppointmentTypeList(state, data){
            state.staticData.appointmentTypeList = data;
        },
        setClients(state, data){
            state.users = data;
        },
        setUsers(state, data){
            state.users = data;
        },
        setLegalCasesBy(state, {data, userID}){
            Vue.set(state.legalCases, userID, data);
        },
        setLegalCases(state, data){
            state.legalCases = data;
        },
        setIsClientInUse(state, data){
            state.isClientInUse = data;
        },
        setClientForm(state, data){
            state.clientForm = data;
        },
        setIsLegalCaseInUse(state, data){
            state.isLegalCaseInUse = data;
        },
        setLegalCaseForm(state, data){
            state.legalCaseForm = data;
        },
        setCurrentLegalCaseUserId(state, data){
            state.currentLegalCaseUserId = data;
        },
        setPaymentDatesForm(state, data){
            state.paymentDates = data;
        },
        setLegalCaseNotesBy(state, {data, legalCaseID}){
            Vue.set(state.legalCaseNotes, legalCaseID, data);
        },
        setLegalCaseNotes(state, data){
            state.legalCaseNotes = data;
        },
        setLegalPaymentDatesBy(state, {data, legalCaseID}){
            Vue.set(state.legalPaymentDates, legalCaseID, data);
        },
        setLegalPaymentDates(state, data){
            state.legalPaymentDates = data;
        },
        setCurrentUserIdUpdatePassword(state, data){
            state.currentUserIdUpdatePassword = data;
        },
        setShowLoader(state, data){
            state.showLoader = data;
        },
        setLegalCasePaymentDates(state, data){
            state.legalCasePaymentDates = data;
        },
        setAppointmentsDates(state, data){
            state.appointmentsDates = data;
        },
        setEvents(state, data){
            state.events = data;
        },
        setAppointmentForm(state, data){
            state.appointmentForm = data;
        },
        setAppointmentFormBy(state, {data, by}){
            Vue.set(state.appointmentForm, by, data);
        },
        setAppointmentOriginalClientList(state, data){
            state.appointmentOriginalClientList = data;
        },
        setSearchClientForm(state, data){
            state.searchClientForm = data;
        },
        setUpdatePasswordForm(state, data){
            state.updatePasswordForm = data;
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
        },
        async getIsClientInUse(context, {id}){
            try {
                const data = await repositories.isClientInUse({'id': id});
                const response = data.response;
                let isInUse = 0;
                
                if( response.length ){
                    isInUse = response[0].inUse;
                }
                context.commit('setIsClientInUse', isInUse);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async fillClientForm(context, {id}){
            try {
                const data = await repositories.getClientBy('id', id);
                const response = data.response;
                if( response.length ){
                    context.commit('setClientForm', response[0]);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async updateClientIsInUse(context, {id, inUse}){
            try {
                await repositories.updateClientIsInUse({'id': id, 'inUse': inUse});
                context.commit('setIsClientInUse', inUse);
                
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getIsLegalCaseInUse(context, {id}){
            try {
                const data = await repositories.isLegalCaseInUse({'id': id});
                const response = data.response;
                let isInUse = 0;
                
                if( response.length ){
                    isInUse = response[0].inUse;
                }

                context.commit('setIsLegalCaseInUse', isInUse);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async fillLegalCaseForm(context, {id}){
            try {
                const data = await repositories.getLegalCasesBy('id', id);
                const response = data.response;

                if( response.length ){
                    response[0].id = id;
                    context.commit('setLegalCaseForm', response[0]);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async fillPaymentDatesOnForm(context, {id}){
            try {
                const data = await repositories.getLegalPaymentDatesBy('legalCaseID', id);
                const response = data.response;
                if( response.length ){
                  const tmpData = {
                    legalCaseID : id,
                    dates: response
                  };
                  context.commit('setPaymentDatesForm', tmpData);
                }
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async updateLegalCaseIsInUse(context, {id, inUse}){
            try {
                await repositories.updateLegalCaseIsInUse({'id': id, 'inUse': inUse});
                context.commit('setIsLegalCaseInUse', inUse);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getLegalCaseNotesBy(context, {searchBy, legalCaseID}){
            try {
                const data = await repositories.getLegalCaseNotesBy(searchBy, legalCaseID);
                const response = data.response;
                context.commit('setLegalCaseNotesBy', {data:response, legalCaseID});
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getLegalPaymentDatesBy(context, {searchBy, legalCaseID}){
            try {
                const data = await repositories.getLegalPaymentDatesBy(searchBy, legalCaseID);
                const response = data.response;
                context.commit('setLegalPaymentDatesBy', {data:response, legalCaseID});
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        }, async getAppointmentTypeList(context){
            try {
                const data = await repositories.getAppointmentTypeList();
                const response = data.response;
                context.commit('setAppointmentTypeList', response);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getPaymentDatesByDateRange(context, {startDate, endDate}){
            try {
                const data = await repositories.getPaymentDatesByDateRange(startDate, endDate);
                const response = data.response;
                context.commit('setLegalCasePaymentDates', response);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async getAppointmentsByDateRange(context, {searchBy, startDate, endDate}){
            try {
                const data = await repositories.getAppointmentsByDateRange(searchBy, startDate, endDate);
                const response = data.response;
                context.commit('setAppointmentsDates', response);
            }catch (error) {
                //context.commit('showError', error);
                alert(error);
            }
        },
        async fillAppointmentForm(context){
            
            const buildUserClientName = function(data){
                data.forEach(item => {
                item.client = item.personalID + ' -> ' + item.name + ' ' + item.lastName1 + ' ' + item.lastName2;
                item.userID = item.id;
                });
                return data;
            }

            try {

                const dataClients = await repositories.getAllClients();
                const responseClients = dataClients.response;

                const clientsFormatted = buildUserClientName(responseClients);
                context.commit('setAppointmentFormBy', {data:clientsFormatted, by:'clientList'});
                context.commit('setAppointmentOriginalClientList', clientsFormatted);

                const dataUsers = await repositories.getAllUsers();
                const responseUsers = dataUsers.response;

                const usersFormatted = buildUserClientName(responseUsers);
                context.commit('setAppointmentFormBy', {data:usersFormatted, by:'usersList'});

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