var global = {
   checkAccessList: function(actionID){
       return loggedINAccessList.includes(actionID);
   }
}
  
export default global