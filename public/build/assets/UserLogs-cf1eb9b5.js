import{T as V,e as v,k as N,o as m,f as P,w as r,b as e,a as i,h as l,i as f,t as s,c as p,F as A,r as B,d as w,l as b,u as _,O as k,n as $}from"./app-ce342ae0.js";import{_ as C}from"./SecondaryButton-a32aaaa8.js";import{_ as E}from"./Modal-56038277.js";import{_ as D}from"./AuthenticatedLayout-5e82fb2a.js";import{P as R}from"./PrimaryButton-d9c0da88.js";import{_ as U}from"./ChangeMonth-4829d30e.js";import"./ApplicationLogo-eac321ad.js";import"./_plugin-vue_export-helper-c27b6911.js";const q=e("h1",{class:"font-semibold text-2xl text-gray-800 leading-tight"},"Employee logs",-1),F={class:"flex items-center justify-between p-1 bm-3"},z={class:"text-xl"},I={class:"flex"},W={class:"text-xl"},Y={class:"flex-1 overflow-auto"},G={key:0,class:"text-center w-full pt-10"},H={key:1,class:"min-w-full w-[900]"},J=e("thead",{class:"bg-gray-200 border-b"},[e("tr",null,[e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," LP. "),e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," Date "),e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," Enter "),e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," Exit "),e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," Logs "),e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," Working time "),e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," Premia ")])],-1),K={class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"},Q={class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"},X={class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"},Z={class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"},ee=["onClick"],te={class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"},se={class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"},oe=e("div",{class:"py-8 text-center text-sm text-gray-400"},[e("br")],-1),le={ref:"loadMoreIntersect"},ne={class:"p-6"},ae={class:"text-lg font-medium text-gray-900"},re={class:"flex justify-evenly mb-4"},de={class:"text-center p-2"},ie={class:"p-2"},ce={class:"text-center p-2"},me={class:"p-2"},ue={class:"flex justify-between"},xe={class:"ml-20 text-center w-[100]"},fe=e("thead",{class:"bg-gray-200 border-b"},[e("tr",null,[e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," LP. "),e("th",{class:"text-sm font-medium text-gray-900 px-6 py-4 text-left"}," Time record ")])],-1),pe={class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"},_e={class:"px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"},he=e("label",{for:"time"},"Set new record:",-1),ge=["formDate"],ye={class:"mt-6 flex justify-end mt-20"},ve={class:"p-6"},we={class:"text-lg font-medium text-gray-900"},be={class:"flex justify-evenly my-10"},ke={class:"flex flex-col text-center"},Ce=e("label",{for:"date"},"Set date:",-1),Re={class:"flex flex-col text-center"},je=e("label",{for:"time"},"Set time:",-1),Ae={class:"mt-6 flex justify-end mt-20"},Ne={__name:"UserLogs",props:{id:String,user:Object,recordAdded:String,daysData:Object},setup(u){const d=u,c=V({newRecord:null}),h=v(!1),g=v(!1),o=v();N(()=>d.recordAdded,T);function M(n){console.log(n),o.value=n,h.value=!0}function O(){g.value=!0}function y(){c.newRecord=null,o.value={},h.value=!1,g.value=!1}function j(n){if(n in d.daysData&&d.daysData[n].logs.includes(c.newRecord+":00")){alert("This record already exists.");return}n&&c.newRecord?k.put(route("user.addLog",d.id),{newRecord:n+" "+c.newRecord}):alert("Enter the correct date and time"),y()}const S=n=>{k.post(route("user.logs.period",d.id),{date:n})};function T(){d.recordAdded&&(alert("Rekord "+d.recordAdded+" has been added to the database"),location.reload(!0),d.recordAdded="")}function L(){k.get(route("user.bill",d.id))}return(n,a)=>(m(),P(D,null,{header:r(()=>[q]),default:r(()=>[e("nav",F,[e("div",z,[i(U,{onPeriod:S})]),e("div",I,[i(R,{class:"mr-5",onClick:f(O,["prevent"])},{default:r(()=>[l("Add new record")]),_:1}),i(C,{class:"mr-5",onClick:f(L,["prevent"])},{default:r(()=>[l("Bill")]),_:1}),e("p",W,"User: "+s(u.user.name),1)])]),e("div",Y,[Object.keys(u.daysData).length?(m(),p("table",H,[J,e("tbody",null,[(m(!0),p(A,null,B(u.daysData,(t,x)=>(m(),p("tr",{key:x,class:"bg-white border-b"},[e("td",K,s(Object.keys(u.daysData).indexOf(x)+1),1),e("td",Q,s(x),1),e("td",X,s(t.logs[0]),1),e("td",Z,s(t.logs[t.logs.length-1]),1),e("td",{onClick:()=>M([x,t]),class:$(["px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 transition duration-300 ease-in-out hover:bg-blue-100 cursor-pointer text-center",{"bg-red-200":t.logs.length%2}])},s(t.logs.length)+" "+s(t.logs.length%2?"(error)":"")+" ",11,ee),e("td",te,s(t.work_time),1),e("td",se,s(t.premia?"YES":"NO"),1)]))),128))])])):(m(),p("p",G,"The database does not contain any records.")),oe,e("div",le,null,512)]),i(E,{show:h.value},{default:r(()=>[e("div",ne,[e("h2",ae," Date: "+s(o.value[0]),1),e("div",re,[e("div",null,[e("div",de,[l("Enter: "),e("strong",null,s(o.value[1].logs[0]),1)]),e("div",ie,[l("Exit: "),e("strong",null,s(o.value[1].logs[o.value[1].logs.length-1]),1)])]),e("div",null,[e("div",ce,[l("Time at work: "),e("strong",null,s(o.value[1].time),1)]),e("div",me,[l("Break time: "),e("strong",null,s(o.value[1].break_time),1)])])]),e("div",ue,[e("table",xe,[fe,e("tbody",null,[(m(!0),p(A,null,B(o.value[1].logs,(t,x)=>(m(),p("tr",null,[e("td",pe,s(x+1),1),e("td",_e,s(t),1)]))),256))])]),e("form",{onSubmit:a[1]||(a[1]=f(t=>j(o.value[0]),["prevent"])),class:"flex flex-col justify-end items-center"},[he,w(e("input",{"onUpdate:modelValue":a[0]||(a[0]=t=>_(c).newRecord=t),formDate:o.value[0],type:"time",id:"time1",required:""},null,8,ge),[[b,_(c).newRecord]]),e("div",ye,[i(C,{onClick:f(y,["prevent"])},{default:r(()=>[l("Cancel")]),_:1}),i(R,{class:"ms-3"},{default:r(()=>[l("Add record")]),_:1})])],32)])]),l(" "+s(),1)]),_:1},8,["show"]),i(E,{show:g.value},{default:r(()=>[e("div",ve,[e("h2",we," Add new record for user: "+s(u.user.name),1),e("form",null,[e("div",be,[e("div",ke,[Ce,w(e("input",{"onUpdate:modelValue":a[2]||(a[2]=t=>o.value=t),type:"date",id:"date",max:"2030-12-31",required:""},null,512),[[b,o.value]])]),e("div",Re,[je,w(e("input",{"onUpdate:modelValue":a[3]||(a[3]=t=>_(c).newRecord=t),type:"time",id:"time2",name:"time",required:""},null,512),[[b,_(c).newRecord]])])]),e("div",Ae,[i(C,{onClick:f(y,["prevent"])},{default:r(()=>[l("Cancel")]),_:1}),i(R,{onClick:a[4]||(a[4]=f(t=>j(o.value),["prevent"])),class:"ms-3"},{default:r(()=>[l("Add record")]),_:1})])])])]),_:1},8,["show"])]),_:1}))}};export{Ne as default};
