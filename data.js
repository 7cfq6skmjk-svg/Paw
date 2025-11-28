const STORAGE_KEY='students';
function loadStudents(){const j=localStorage.getItem(STORAGE_KEY);if(!j)return [];try{return JSON.parse(j)}catch(e){return []}}
function saveStudents(s){localStorage.setItem(STORAGE_KEY,JSON.stringify(s))}
function ensureSeed(){let s=loadStudents();if(s.length===0){s=[{last:'za9zo9',first:'nazim',id:'106',email:'naz@example.com',sessions:[true,false,true,true,false,true],participation:[true,true,false,true,false,false]},{last:'cheraga',first:'wacim',id:'102',email:'ali@example.com',sessions:[false,true,false,true,true,true],participation:[true,false,true,false,false,true]},{last:'wlid alger',first:'aymen',id:'1003',email:'rania@example.com',sessions:[true,true,false,false,true,false],participation:[false,true,false,false,false,false]}];saveStudents(s)}else{if(s[0]){s[0].last='za9zo9';s[0].first='nazim'}if(s[1]){s[1].last='cheraga';s[1].first='wacim'}if(s[2]){s[2].last='wlid alger';s[2].first='aymen'}saveStudents(s)}return s}
function presentCount(stu){return stu.sessions.filter(Boolean).length}
function absencesCount(stu){return 6-presentCount(stu)}
function participationCount(stu){return stu.participation.filter(Boolean).length}
function classifyRow(abs){if(abs>=5)return'red';if(abs>=3)return'yellow';return'green'}
function makeMessage(abs,par){if(abs>=5)return'Excluded — too many absences';if(abs>=3){return par<3?'Warning — attendance low — You need to participate more':'Warning — attendance low'}return par>=4?'Good attendance — Excellent participation':'Good attendance — You need to participate more'}
window.AttData={loadStudents,saveStudents,ensureSeed,presentCount,absencesCount,participationCount,classifyRow,makeMessage}
