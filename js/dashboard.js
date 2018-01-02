
$(document).ready(function(){
    jconfirm.defaults = {
        icon: 'fa fa-warning',
        backgroundDismiss: false,
        backgroundDismissAnimation: 'glow',
        escapeKey: true,
        closeIcon:true,
        theme:'modern',
        title: 'Are You Sure?',
        autoClose: 'Cancel|15000',
        animation: 'scaleX',
        animationSpeed: 500,
        type: 'red',
        animationBounce: 1.5,
    }
    var allStudentsData;

    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $(".side-nav .collapse").on("hide.bs.collapse", function() {                   
            $(this).prev().find(".fa").eq(1).removeClass("fa-angle-right").addClass("fa-angle-down");
        });
        $('.side-nav .collapse').on("show.bs.collapse", function() {                        
            $(this).prev().find(".fa").eq(1).removeClass("fa-angle-down").addClass("fa-angle-right");        
        });
    })   


    
 
    

    allStudentsTable = function(){
        $('#mainContainerHead').empty();
        $('#mainContainerHead').append('<h1>All Students Record</h1>');
        $('#sectionTitle').find('b').text('Records');
        $('#mainContainer').empty();
        $('#mainContainerButtons').css('display','none');
        $('#mainContentHead').empty();
        $('#uploadMonthlyCsvContainer').css("display","none")
        $('#uploadAllStudentsContainer').css("display","block")

        $('#mainContainer').append('<table class="table  table-hover display dt[-head|-body]-center table-border" id="studentsTable" style="width: 100%"><thead><tr id="studentsTableHeader"></tr></thead></table>')
        
        var table_head = '<th>ID</th><th>ROLL NO</th><th>NAME</th><th>PROGRAM</th><th>EMAIL</th><th>AADHAR</th><th>REGISTRATION DATE</th><th>CESSATION DATE</th><th>EDIT</th><th>LEAVES</th>';
        var columnData = [];
        columnData.push({"data": "id"});
        columnData.push({"data": "rollno"});    
        columnData.push({"data": "name"});
        columnData.push({"data": "program_name"});
        columnData.push({"data": "email"}); 
        columnData.push({"data": "aadhar"}); 
        columnData.push({"data": "program_start_date"});
        columnData.push({"data": "end_date"});  
        var obj = { targets : [4],
            "data": "id",
            "render" : function (data, type, row) {
            var elem = '<a class="btn icon-btn btn-info editStudentData"><span class="fa fa-edit"></span>&nbspEDIT</a>';
            return elem;
            }
        }
        columnData.push(obj); 
        var obj = { targets : [4],
            "data": "id",
            "render" : function (data, type, row) {
            var elem = '<a class="btn icon-btn btn-info viewStudentData"><span class="fa fa-edit"></span>&nbspLEAVES</a>';
            return elem;
            }
        }
        columnData.push(obj); 


        $('#studentsTableHeader').append(table_head);

        $('#studentsTable').DataTable({
            "order": [[ 1, "asc" ]],
            "bProcessing": true,
            "scrollX": true,
            "responsive":true,
            "serverSide": true,
            // "jQueryUI": true,
            "columnDefs": [ {"className": "dt-center", "targets": "_all"}, {"className": "dt-body_center"} ] ,
            language: {
                search: "_INPUT_",
            },
            ajax: {
                url: 'utils/getStudentsData.php',
                "type": "POST",
                "datatype": "json",
                "data":{tab:'all', month:'12', year:'2017'},
                dataFilter: function(data){
                    console.log(data)
                    var json = jQuery.parseJSON( data );
                    var temp = json.studentArray;
                    allStudentsData = temp.data;
                    // console.log(temp.data);
                    return JSON.stringify( json.studentArray ); // return JSON string
                }
            },
        "columns": columnData,
            
        });
    }



    


    allProgramsTable = function(){
       
        $.ajax({
            url:"./utils/getProgramData.php",
            method:"POST",
            data:"",
            DataType:"json",
            success:function(data){
                //json.parseJSON(data);
                //JSON.stringify(data);
                 var json = jQuery.parseJSON( data );
                 console.log(json.length);
                 var x=json.length;
                console.log(data);
                $('#programsTable tbody').text('');
                for(var i=0;i<x;i++)
                {
                    $('#programsTable tbody').append('<tr><td>'+(i+1)+'</td><td>'+json[i]['program_name']+'</td><td>'+json[i]['stipend']+'</td><td>'+json[i]['tenure_months']+'</td><td>'+json[i]['sanctioned']+'</td><td>'+json[i]['medical']+'</td><td>'+json[i]['contingency']+'</td><td>'+json[i]['maternity']+'</td><td>'+json[i]['duty']+'</td><td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs editProgramBtn" ><span class="glyphicon glyphicon-pencil"></span></button></p></td><td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs deleteProgrambtn" data-title="Delete" data-toggle="modal" data-target="#deleteProgram" ><span class="glyphicon glyphicon-trash"></span></button></p></td></tr>');    
                }
                
        
            }
        })
    }

    allUsersTable = function(){
       
        $.ajax({
            url:"./utils/getUser.php",
            method:"POST",
            data:"",
            DataType:"json",
            success:function(data){
                //json.parseJSON(data);
                //JSON.stringify(data);
                 var json = jQuery.parseJSON( data );
                 console.log(json.length);
                 var x=json.length;
                console.log(data);
                $('#usersTable tbody').text('');
                for(var i=0;i<x;i++)
                {
                    $('#usersTable tbody').append('<tr><td>'+(i+1)+'</td><td>'+json[i]['name']+'</td><td>'+json[i]['email']+'</td><td>'+json[i]['role']+'</td><td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs deleteUserbtn" data-title="Delete" data-toggle="modal" data-target="#deleteUser" ><span class="glyphicon glyphicon-trash"></span></button></p></td></tr>');    
                }
                
        
            }
        })
    }


    allIPTable = function(){
       
        $.ajax({
            url:"./utils/getIPData.php",
            method:"POST",
            data:"",
            DataType:"json",
            success:function(data){
                //json.parseJSON(data);
                //JSON.stringify(data);
                 var json = jQuery.parseJSON( data );
                 console.log(json.length);
                 var x=json.length;
                console.log(data);
                $('#ipTable tbody').text('');
                for(var i=0;i<x;i++)
                {
                    $('#ipTable tbody').append('<tr><td>'+(i+1)+'</td><td>'+json[i]['Name']+'</td><td>'+json[i]['ip']+'</td><td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs editIPBtn" ><span class="glyphicon glyphicon-pencil"></span></button></p></td><td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs deleteIPbtn" data-title="Delete" data-toggle="modal" data-target="#deleteIP" ><span class="glyphicon glyphicon-trash"></span></button></p></td></tr>');
                }
                    
        
            }
        })
    }

    

    monthlyRecord = function(month, year){
        
        $('#mainContainerHead').empty();
        $('#mainContainerHead').append('<h1>Monthly Record</h1>');
        $('#sectionTitle').find('b').text('Records');
        $('#mainContainer').empty();
        // $('#mainContainerButtons').css('display','none')
        $('#mainContainerHead').append('<div> <select type="text" placeholder="Month" id="monthInput"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select><select type="text" placeholder="Year" id="yearInput"><option value="2005">2005</option><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option></select><button class="btn btn-warning" type="button" id="formSubmit">Submit</button></div>');
        $('#mainContainer').append('<table class="table  table-hover display dt[-head|-body]-center table-border" id="studentsTable" style="width: 100%"><thead><tr id="studentsTableHeader"></tr></thead></table>')
        $('#uploadMonthlyCsvContainer').css("display","block")
        $('#uploadAllStudentsContainer').css("display","none")
        
        $('#monthInput').val(5);
        $('#yearInput').val(2017);
        
        var table_head = '<th>ID</th><th>ROLL NO</th><th>NAME</th><th>PROGRAM</th><th>PHONE</th><th>P</th><th>A</th><th>SL</th><th>ML</th><th>CL</th><th>MATERNITY</th><th>DUTY</th><th>PAYABLE</th><th>WO</th><th>AMOUNT</th><th>SAVE</th><th>SUBMIT</th>';
        // var allowedLeavesData;

        var columnData = [];
        columnData.push({"data": "id"});
        columnData.push({"data": "rollno"});    
        columnData.push({"data": "name"});
        columnData.push({"data": "program_name"});
        columnData.push({"data": "phone"}); 
        
        // columnData.push({"data": "email"});
        columnData.push({"data": "present"});

        columnData.push({"data": "absent"});
        var obj = { targets : [4],
            "data": "sanctioned",
            "render" : function (data, type, row) {
                // console.log(allowedLeavesData);
                data += 190;
                if(data < 0)
                {
                    var elem =  -data;
                }else
                {
                    var elem = '<input type="text" class="multiLeavesPicker inputFieldMonthlyTable" value=""/>';
                    
                }
                return elem;
            }
        };
        columnData.push(obj); 
        var obj = { targets : [4],
            "data": "medical",
            "render" : function (data, type, row) {
                
                if(data < 0)
                {
                    data += 190;
                    var elem =  -data;
                }else
                {
                    var elem = '<input type="text" class="multiLeavesPicker inputFieldMonthlyTable" value=""/>';
                    // var elem = '<select type="text" value="">';
                    // for(var i=0;i<=8;i++)
                    // {
                    //     elem += '<option value="'+i+'">'+i+'</option>';
                    // }
                    // elem += '</select>';
                    
                }
                return elem;
            }
        };
        columnData.push(obj); 
        var obj = { targets : [4],
            "data": "contingency",
            "render" : function (data, type, row) {
                
                if(data < 0)
                {
                    data += 190;
                    var elem =  -data;
                }else
                {
                    var elem = '<input type="text" class="multiLeavesPicker inputFieldMonthlyTable" value=""/>';
                                        
                }
                return elem;
            }
        };
        columnData.push(obj); 
        var obj = { targets : [4],
            "data": "maternity",
            "render" : function (data, type, row) {
                
                if(data < 0)
                {
                    data += 190;
                    var elem =  -data;
                }else
                {
                    var elem = '<input type="text" class="multiLeavesPicker inputFieldMonthlyTable" value=""/>';
                    
                }
                return elem;
            }
        };
        columnData.push(obj); 
        var obj = { targets : [4],
            "data": "duty",
            "render" : function (data, type, row) {
                
                if(data < 0)
                {
                    data += 190;
                    var elem =  0 - data;
                }else
                {
                    var elem = '<input type="text" class="multiLeavesPicker inputFieldMonthlyTable" value=""/>';
                                        
                }
                return elem;
            }
        };
        columnData.push(obj); 
        columnData.push({"data": "payableDays"}); 
        //columnData.push({"data": "work_off"});
         var obj = { targets : [4],
            "data": "work_off",
            "render" : function (data, type, row) {
                
                if(data < 0)
                {
                    data += 190;
                    var elem =  0 - data;
                }else
                {
                    var elem = '<input type="text" class="multiLeavesPicker inputFieldMonthlyTable" value=""/>';
                                        
                }
                return elem;
            }
        };
        columnData.push(obj);
        columnData.push({"data": "payable"}); 
        var obj = { targets : [4],
            "data": "duty",
            "render" : function (data, type, row) {
                if(data < 0)
                {
                    data += 190;
                    var elem = '<b>DONE</b>';
                }else
                {
                    var elem = '<button class="btn btn-success saveData" type="button" name="submit" class="saveData">Save</button>';
                }
                return elem;
            }
        };
        columnData.push(obj); 

        var obj = { targets : [4],
            "data": "duty",
            "render" : function (data, type, row) {
                if(data < 0)
                {
                    data += 190;
                    var elem = '<b>DONE</b>';
                }else
                {
                    var elem = '<button class="btn btn-danger saveData" type="button" name="submit" class="submitData">Submit</button>';
                }
                return elem;
            }
        };
        columnData.push(obj); 

        $('#studentsTableHeader').append(table_head);

        $('#studentsTable').DataTable({
            "order": [[ 1, "asc" ]],
            "bProcessing": true,
            "scrollX": true,
            "responsive":true,
            "serverSide": true,
            // "jQueryUI": true,
            "columnDefs": [ {"className": "dt-center", "targets": "_all"}, {"className": "dt-body_center"} ] ,
            language: {
                search: "_INPUT_",
            },
            ajax: {
                url: 'utils/monthlyRecord.php',
                "type": "POST",
                "datatype": "json",
                "data":{ month:month, year:year},
                dataFilter: function(data){
                    // columnData = [];
                    var json = jQuery.parseJSON( data );
                    console.log(json)
                    // var temp = json.studentArray;
                    // leads = temp;
                    // console.log('received');
                    allowedLeavesData = json.studentArray.data;
                    // console.log(leads);
                    
                    console.log(allowedLeavesData.length);
                    for(var i = 0; i <allowedLeavesData.length ;i++)
                    {
                        console.log(json.studentArray.data[i]);

                        var sl =  allowedLeavesData[i].total_sanctioned;
                        var ml =  allowedLeavesData[i].total_medical;
                        var cl =  allowedLeavesData[i].total_contingency;
                        var duty =  allowedLeavesData[i].total_duty;
                        var maternity = allowedLeavesData[i].total_maternity;
                        
                        json.studentArray.data[i].sanctioned = sl;
                        json.studentArray.data[i].medical = ml;
                        json.studentArray.data[i].contingency = cl;
                        json.studentArray.data[i].duty = duty;
                        json.studentArray.data[i].maternity = maternity;
                        if(json.studentArray.data[i].form_submitted == 1)
                        {
                            json.studentArray.data[i].sanctioned = -parseInt(sl)-190;
                            json.studentArray.data[i].medical = -parseInt(ml) -190;
                            json.studentArray.data[i].contingency = -parseInt(cl)-190;
                            json.studentArray.data[i].duty = -parseInt(duty)-190;
                            json.studentArray.data[i].maternity = -parseInt(maternity)-190;
                        }   
                        // console.log(json.studentArray.data[i].sanctioned)

                        json.studentArray.data[i].allowed_sanctioned = allowedLeavesData[i].total_sanctioned;
                        json.studentArray.data[i].allowed_medical = allowedLeavesData[i].total_medical;
                        json.studentArray.data[i].allowed_contingency = allowedLeavesData[i].total_contingency;
                        json.studentArray.data[i].allowed_duty = allowedLeavesData[i].total_duty;
                        json.studentArray.data[i].allowed_maternity = allowedLeavesData[i].total_maternity;
                        
                        json.studentArray.data[i].payableDays = parseInt(json.studentArray.data[i].present) + parseInt(allowedLeavesData[i].total_sanctioned) + parseInt(allowedLeavesData[i].total_medical) + parseInt(allowedLeavesData[i].total_contingency) + parseInt(allowedLeavesData[i].total_duty) + parseInt(allowedLeavesData[i].total_maternity);
                        console.log(json.studentArray.data[i]);
                    }
                    
                        
                    return JSON.stringify( json.studentArray ); // return JSON string
                }
            },
        "columns": columnData,
        "drawCallback": function( settings ) {
            $('.multiLeavesPicker').multiDatesPicker({
                dateFormat: "dd/mm/yy",
            });
        }
        });
        
    
        
                
    }

    logout = function()
    {
        $.ajax({
            type:"POST",
            url:"./utils/logout.php",
            success:function(){
                window.location ="./";
            }
        })
    }



    dashboardSetup = function(){
        $('#mainContainerHead').text('Your Dashboard');
        $('#sectionTitle').find('b').text('Records');
        $('#mainContainer').empty();
        $('#mainContainerButtons').css('display','block');
        $('#mainContainer').append('<div class="well text-center"><button type="button" class="btn btn-hot text-capitalize btn-lg" id="printReport">PRINT REPORT</button> <button type="button" class="btn btn-sunny text-uppercase btn-lg" id="managePrograms">Manage Programs</button><button type="button" class="btn btn-fresh text-uppercase btn-lg" id="addUsers">Add Users</button><button type="button" class="btn btn-sky text-uppercase btn-lg" id="manage">Manage Users</button><button type="button" class="btn btn-sky text-uppercase btn-lg" id="ip">ADD IP ADDRESS</button></div>');
        $('#mainContainer').append('<div class="table-responsive" style="display:none" id="programsTableContainer"><table id="programsTable" class="table table-bordred table-striped"><thead><th>S.No</th><th>Name</th><th>Stipend</th><th>Tenure</th><th>Sanctioned</th><th>Contingency</th><th>Medical</th><th>Maternity</th><th>Duty</th><th>Edit</th><th>Delete</th></thead><tbody></tbody></table><button type="button" class="btn btn-success btn-md" id="addProgramBtn">Add New</button>');
        $('#mainContainer').append('<div class="table-responsive" style="display:none" id="ipTableContainer"><table id="ipTable" class="table table-bordred table-striped"><thead><th>S.No</th><th>Name</th><th>IP ADDRESS</th><th>Edit</th><th>Delete</th></thead><tbody></tbody></table><button type="button" class="btn btn-success btn-md" id="addIPBtn">Add New</button>');
        $('#mainContainer').append('<div class="table-responsive" style="display:none" id="userTableContainer"><table id="usersTable" class="table table-bordred table-striped"><thead><th>S.No</th><th>Name</th><th>email</th><th>role</th><th>Delete</th></thead><tbody></tbody></table>');
        //$('#programsTable tbody').append('<tr><td>1</td><td>IPG</td><td>12400</td><td>12</td><td>85</td><td>5</td><td>55</td><td>69</td><td>23</td><td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs editProgramBtn" ><span class="glyphicon glyphicon-pencil"></span></button></p></td><td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#deleteProgram" ><span class="glyphicon glyphicon-trash"></span></button></p></td></tr>');
        //$('#ipTable tbody').append('<tr><td>1</td><td>name1</td><td>192.168.1.1</td><td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs editIPBtn" ><span class="glyphicon glyphicon-pencil"></span></button></p></td><td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#deleteIP" ><span class="glyphicon glyphicon-trash"></span></button></p></td></tr>');
        if($('#uploadMonthlyCsvContainer').css("display") == "block");
            $('#uploadMonthlyCsvContainer').css("display","none");
        if($('#uploadAllStudentsContainer').css("display") == "block");
            $('#uploadAllStudentsContainer').css("display","none");

        (function() {
            var removeSuccess;

            removeSuccess = function() {
            return $('.button').removeClass('success');
        };

        $("[data-toggle=tooltip]").tooltip();

        $(document).ready(function() {
            return $('.button').click(function() {
              $(this).addClass('success');
              return setTimeout(removeSuccess, 3000);
            });
        });

        }).call(this);
        $('#mainContainerHead').empty();
        $('#mainContainerHead').append('<h2>Welcome to Dashboard</h2>');
    }
    dashboardSetup();




    statusSetup = function(){
       
        $('#mainContainer').empty();
        $('#mainContainerButtons').css('display','block');
        $('#mainContainer').append('<div class="well text-center"><button type="button" class="btn btn-hot text-capitalize btn-lg" id="actives">Active</button> <button type="button" class="btn btn-sunny text-uppercase btn-lg" id="inactives">Inactive</button></div>');
        $('#mainContainer').append('<div class="table-responsive" style="display:none" id="programsTableContainer"><table id="programsTable" class="table table-bordred table-striped"><thead><th>S.No</th><th>Name</th><th>Stipend</th><th>Tenure</th><th>Sanctioned</th><th>Contingency</th><th>Medical</th><th>Maternity</th><th>Duty</th><th>Edit</th><th>Delete</th></thead><tbody></tbody></table><button type="button" class="btn btn-success btn-md" id="addProgramBtn">Add New</button>');
        $('#mainContainer').append('<div class="table-responsive" style="display:none" id="ipTableContainer"><table id="ipTable" class="table table-bordred table-striped"><thead><th>S.No</th><th>Name</th><th>IP ADDRESS</th><th>Edit</th><th>Delete</th></thead><tbody></tbody></table><button type="button" class="btn btn-success btn-md" id="addIPBtn">Add New</button>');
        $('#mainContainer').append('<div class="table-responsive" style="display:none" id="userTableContainer"><table id="usersTable" class="table table-bordred table-striped"><thead><th>S.No</th><th>Name</th><th>email</th><th>role</th><th>Delete</th></thead><tbody></tbody></table>');
        //$('#programsTable tbody').append('<tr><td>1</td><td>IPG</td><td>12400</td><td>12</td><td>85</td><td>5</td><td>55</td><td>69</td><td>23</td><td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs editProgramBtn" ><span class="glyphicon glyphicon-pencil"></span></button></p></td><td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#deleteProgram" ><span class="glyphicon glyphicon-trash"></span></button></p></td></tr>');
        //$('#ipTable tbody').append('<tr><td>1</td><td>name1</td><td>192.168.1.1</td><td><p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs editIPBtn" ><span class="glyphicon glyphicon-pencil"></span></button></p></td><td><p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs" data-title="Delete" data-toggle="modal" data-target="#deleteIP" ><span class="glyphicon glyphicon-trash"></span></button></p></td></tr>');
        if($('#uploadMonthlyCsvContainer').css("display") == "block");
            $('#uploadMonthlyCsvContainer').css("display","none");
        if($('#uploadAllStudentsContainer').css("display") == "block");
            $('#uploadAllStudentsContainer').css("display","none");

        (function() {
            var removeSuccess;

            removeSuccess = function() {
            return $('.button').removeClass('success');
        };

        $("[data-toggle=tooltip]").tooltip();

        $(document).ready(function() {
            return $('.button').click(function() {
              $(this).addClass('success');
              return setTimeout(removeSuccess, 3000);
            });
        });

        }).call(this);
        $('#mainContainerHead').empty();
        $('#mainContainerHead').append('<h2>Welcome to Dashboard</h2>');
    }
    dashboardSetup();




    arearsSetup = function()
    {
        // console.log('sdad')
        $('#mainContainer').empty();
        $('#mainContainerHead').empty();
        $('#mainContainerHead').append('<h1>Arears</h1>');

        $('#mainContainer').append('<div id="fullscreen_bg" class="fullscreen_bg"/><img src="./css/page_under_construction.png "style="width:100%"></div>');

    }

    $(document.body).on('click', '#addProgramBtn', function(){
        $('#editProgramModalConfirmBtn').removeClass('btn-warning')
        $('#editProgramModalConfirmBtn').addClass('btn-success')
        $('#editProgramModalTitle').text('ADD PROGRAM')
        $('#editProgramModalConfirmBtn').text('Add')
        $('#editProgram').modal('toggle');

        $('#editName').val(0);
        $('#editStipend').val(0);
        $('#editTenure').val('0');
        $('#editSanctioned').val('0');
        $('#editContingency').val('0');
        $('#editMedical').val('0');
        $('#editMaternity').val('0');
        $('#editDuty').val('0');

    })

     $(document.body).on('click', '#addUsers', function(){
        $('#editUserModalConfirmBtn').removeClass('btn-warning')
        $('#editUserModalConfirmBtn').addClass('btn-success')
        $('#editUserModalTitle').text('ADD USER')
        $('#editUserModalConfirmBtn').text('Add')
        $('#editUser').modal('toggle');

        $('#editUserName').val();
        $('#editEmail').val();
        $('#editPassword').val('');
        

    })
    // $('#editIPusername').editableSelect();

     $(document.body).on('click', '#addIPBtn', function(){
        $('#editIPModalConfirmBtn').removeClass('btn-warning')
        $('#editIPModalConfirmBtn').addClass('btn-success')
        $('#editIPModalTitle').text('ADD IP')
        $('#editIPModalConfirmBtn').text('Add')
        $('#editIP').modal('toggle');

        $('#editIPusername').val(0);
        $('#editIPaddr').val(0);
       

    })

    $(document.body).on('click', '.editProgramBtn', function(){
        $('#editProgramModalConfirmBtn').removeClass('btn-success')
        $('#editProgramModalConfirmBtn').addClass('btn-warning')
        $('#editProgramModalTitle').text('UPDATE CONFIRMATION')
        $('#editProgramModalConfirmBtn').text('Update')
        $('#editProgram').modal('toggle');

        $('#editName').val($($(this).parent().closest('tr').find('td')[1]).text());
        $('#editStipend').val($($(this).parent().closest('tr').find('td')[2]).text());
        $('#editTenure').val($($(this).parent().closest('tr').find('td')[3]).text());
        $('#editSanctioned').val($($(this).parent().closest('tr').find('td')[4]).text());
        $('#editContingency').val($($(this).parent().closest('tr').find('td')[5]).text());
        $('#editMedical').val($($(this).parent().closest('tr').find('td')[6]).text());
        $('#editMaternity').val($($(this).parent().closest('tr').find('td')[7]).text());
        $('#editDuty').val($($(this).parent().closest('tr').find('td')[8]).text());

    })
    
    $(document.body).on('click', '.editIPBtn', function(){
        $('#editIPModalConfirmBtn').removeClass('btn-success')
        $('#editIPModalConfirmBtn').addClass('btn-warning')
        $('#editIPModalTitle').text('UPDATE CONFIRMATION')
        $('#editIPModalConfirmBtn').text('Update')
        $('#editIP').modal('toggle');

        $('#editIPusername').val($($(this).parent().closest('tr').find('td')[1]).text());
        $('#editIPaddr').val($($(this).parent().closest('tr').find('td')[2]).text());
        

    })
    $(document.body).on('click', '#editProgramModalConfirmBtn', function(){

        
        var programName = $('#editName').val();
        var stipend = $('#editStipend').val();
        var tenure = $('#editTenure').val();
        var sl = $('#editSanctioned').val();
        var cl = $('#editContingency').val();
        var ml = $('#editMedical').val();
        var maternity = $('#editMaternity').val();
        var duty = $('#editDuty').val();

        
        var content = $('#editProgramModalConfirmBtn').text();
        if(content == 'Update')
            var url = "./utils/updateProgram.php";
        else
            var url = "./utils/addProgram.php";
        $.ajax({
            type:"POST",
            url:url,
            data:{programName:programName, stipend:stipend, tenure:tenure, sl:sl, cl:cl, ml:ml, maternity:maternity, duty:duty},
            success:function(){
                $.alert("Programs list updated successfully");
                window.setTimeout(function(){
                $('.jconfirm-box .btn').on('click',function(){
                    window.location='dashboard.html';
                }); },1000);
                    
                
                
                
            }

        })

    })



   


    $(document.body).on('click', '#ip', function(){

        
    

            var url = "./utils/getUser.php";
        $.ajax({
            type:"POST",
            url:url,
            data:{},
            success:function(data){
                var json = jQuery.parseJSON( data );
                 console.log(json.length);
                 var x=json.length;
                console.log(data);
                $('#editIPusername').text('');
                $('#editIPusername').eComboBox();
                for(var i=0;i<x;i++)
                {
                    $('#editIPusername').append('<option>'+json[i]['name']+'</option>');
                }                
            }

        })

    })

     $(document.body).on('click', '#editIPModalConfirmBtn', function(){

        
        var user = $('#editIPusername').find(":selected").text();
        if(user=="{NEW ELEMENT}"){
            user = $('#editIPusername').parent().children('input').val();
        }
        var addr = $('#editIPaddr').val();
        
       var content = $('#editIPModalConfirmBtn').text();
        if(content == 'Update')
            var url = "./utils/updateIP.php";
        else
            var url = "./utils/addIP.php";
        $.ajax({
            type:"POST",
            url:url,
            data:{user:user,addr:addr},
            success:function(){
                $.alert("USER with IP updated successfully");
                window.setTimeout(function(){
                $('.jconfirm-box .btn').on('click',function(){
                    window.location='dashboard.html';
                }); },1000);
                
            }

        })

    })


 $(document.body).on('click', '#editUserModalConfirmBtn', function(){

        
        var user = $('#editUserName').val();
        var email = $('#editEmail').val();
        var password = $('#editPassword').val();
        var role = $('#editUserRole').find(":selected").text();
       

            var url = "./utils/addUser.php";
        $.ajax({
            type:"POST",
            url:url,
            data:{userName:user, email:email , password:password,role:role},
            success:function(){
                $.alert("User added successfully");
                window.setTimeout(function(){
                $('.jconfirm-box .btn').on('click',function(){
                    window.location='dashboard.html';
                }); },1000);
                
            }

        })

    })

    $(document.body).on('click', '.deleteProgrambtn', function(){

       // console.log("asdsad");
        var programName = $($(this).parent().parent().closest('tr').find('td')[1]).text();
//console.log(programName);
        $('#deleteprogramname').html(programName);
    

    })


    $(document.body).on('click', '#deleteprogramyes', function(){

        console.log("asdsad");
        var programName = $('#deleteprogramname').text();
console.log(programName);
    
        
            var url = "./utils/deleteProgram.php";
        $.ajax({
            type:"POST",
            url:url,
            data:{programName:programName},
            success:function(){
                $.alert("Programs deleted successfully");
                window.setTimeout(function(){
                $('.jconfirm-box .btn').on('click',function(){
                    window.location='dashboard.html';
                }); },1000);                
            }

        })

    })


    $(document.body).on('click', '.deleteIPbtn', function(){

        console.log("asdsad");
        var username = $($(this).parent().parent().closest('tr').find('td')[1]).text();
console.log(username);
        $('#deleteusername').html(username);
    

    })

      $(document.body).on('click', '#deleteipyes', function(){

        console.log("asdsad");
        var username = $('#deleteusername').text();
console.log(username);
    
        
            var url = "./utils/deleteIP.php";
        $.ajax({
            type:"POST",
            url:url,
            data:{username:username},
            success:function(){
                $.alert("IP deleted successfully");
                window.setTimeout(function(){
                $('.jconfirm-box .btn').on('click',function(){
                    window.location='dashboard.html';
                }); },1000);
                
            }

        })

    })





      $(document.body).on('click', '.deleteUserbtn', function(){

        console.log("asdsad");
        var name = $($(this).parent().parent().closest('tr').find('td')[1]).text();
console.log(name);
        $('#deletename').html(name);
    

    })

      $(document.body).on('click', '#deleteuseryes', function(){

        console.log("asdsad");
        var name = $('#deletename').text();
console.log(name);
    
        
            var url = "./utils/deleteUser.php";
        $.ajax({
            type:"POST",
            url:url,
            data:{name:name},
            success:function(){
                $.alert("User deleted successfully");
                window.setTimeout(function(){
                $('.jconfirm-box .btn').on('click',function(){
                    window.location='dashboard.html';
                }); },1000);
                
            }

        })

    })

    $(document.body).on('click', '.viewStudentData', function(){
        $('#LeavesDetails').modal('toggle');

    })


    $(document.body).on('click', '.editStudentData', function(){

        console.log(allStudentsData);
        $('#editStudentData').modal('toggle');
        var index = $(this).parent().closest('tr').index();
        console.log(index);
        $('#editStudentName').val(allStudentsData[index].name);
        $('#editStudentRollNo').val(allStudentsData[index].rollno);
        $('#editStudentProgram').val(allStudentsData[index].program_name);
        $('#editStudentEmail').val(allStudentsData[index].email);
        $('#editStudentAadhar').val(allStudentsData[index].aadhar);
        $('#editStudentPhone').val(allStudentsData[index].phone);
        $('#editStudentAccountNo').val(allStudentsData[index].bank_ac_number);
        $('#editStudentAccountNo').val('yes');        
        // end_date = "2016-11-13";
        var end_date = allStudentsData[index].end_date;
        var result = end_date.split('-');
        console.log(result);
        var cessationDate = result[0]+"-"+result[1]+"-"+result[2];
        $('#editStudentCessationDate').val(cessationDate);
    })

    $(document.body).on('click', '#editStudentModalConfirmBtn', function(){

        console.log(allStudentsData);
        $('#editStudentData').modal('toggle');
        var index = $(this).parent().closest('tr').index();
        console.log(index);

        var name = $('#editStudentName').val();
        var rollno = $('#editStudentRollNo').val();
        var programName = $('#editStudentProgram').val();
        var email = $('#editStudentEmail').val();
        var aadhar = $('#editStudentAadhar').val();
        var phone = $('#editStudentPhone').val();
        var bank = $('#editStudentPhone').val();
        var cessationDate = $('#editStudentCessationDate').val(); 
        var enroll = $('#editStudentenroll').val(); 
        
        


        
        $.ajax({
            type:"POST",
            url:"utils/updateStudent.php    ",
            data:{name:name, rollno:rollno, programName:programName, email:email, enrolled:enroll,aadhar:aadhar, phone:phone , bank:bank, cess:cessationDate},
            success:function(){
                $.alert("Student details successfully updated");
            }

        })


    })


$(document.body).on('click', '#managePrograms', function(){
    $('#programsTable tbody').text('');
    allProgramsTable();
});

$(document.body).on('click', '#manage', function(){
    $('#usersTable tbody').text('');
    allUsersTable();
});

$(document.body).on('click', '#ip', function(){
     $('#ipTable tbody').text('');
    allIPTable();
});

    
    $(document.body).on('click', '.treeview', function(){
        $('.treeview').removeClass('active');
        $(this).addClass('active');
        var tab = $($($(this)[0])[0]).find('span').text();
        if(tab == 'All Students')
            allStudentsTable();
        else if(tab=='Monthly')
        {
            
            monthlyRecord(5, 2017);
        }else if(tab=='Arears')
        {
            console.log('dasd');
            arearsSetup()
        }
        else if(tab=='Status')
        {
            console.log("status");
            statusSetup();
        }
        else
            dashboardSetup();
        console.log('asd'+tab+'asd');
        

    })

    $(document.body).on('click', '#managePrograms', function(){

        $('#ipTableContainer').slideUp();
        $('#userTableContainer').slideUp();        
        if($("#programsTableContainer").is(':visible'))
        {
            $('#programsTableContainer').slideUp()
        }else
        {
            $('#programsTableContainer').slideDown();
        }
    })

    $(document.body).on('click', '#manage', function(){

        $('#ipTableContainer').slideUp();
        $('#programsTableContainer').slideUp();
        
        if($("#userTableContainer").is(':visible'))
        {
            $('#userTableContainer').slideUp();
        }else
        {
            $('#userTableContainer').slideDown();
        }
    })

    $(document.body).on('click', '#ip', function(){
        
        $('#programsTableContainer').slideUp();
        $('#userTableContainer').slideUp();

        if($("#ipTableContainer").is(':visible'))
        {
            $('#ipTableContainer').slideUp();
        }else
        {
            $('#ipTableContainer').slideDown();
        }
    })
    

    $(document.body).on('click', '#formSubmit', function(){
        var month = $('#monthInput').val();
        var year = $('#yearInput').val();
        if(month == '' || year == '')
        {
            $.alert('Please enter month and year to proceed.');
        }else
        {
            monthlyRecord(month, year);
        }
    })



    $(document.body).on('click', '.saveData', function(){
        $(this).prop('disabled', true);
        var id = $($(this).parent().closest('tr').find('td')[0]).text();
        var program_name = $($(this).parent().closest('tr').find('td')[3]).text();
        var presents = $($(this).parent().closest('tr').find('td')[5]).text();
        var absents = $($(this).parent().closest('tr').find('td')[6]).text();
        var sl = $($(this).parent().closest('tr').find('td')[7]).find('input').val();
        var ml = $($(this).parent().closest('tr').find('td')[8]).find('input').val();
        var cl = $($(this).parent().closest('tr').find('td')[9]).find('input').val();
        var maternity = $($(this).parent().closest('tr').find('td')[10]).find('input').val();
        var duty = $($(this).parent().closest('tr').find('td')[11]).find('input').val();
        // var sl = $('.multiLeavesPicker')[0].value;
        // var ml = $('.multiLeavesPicker')[1].value;
        // var cl = $('.multiLeavesPicker')[2].value;
        // var maternity = $('.multiLeavesPicker')[3].value;
        // var duty =  $('.multiLeavesPicker')[4].value;
        console.log(sl)
        console.log(ml)
        console.log(cl)
        console.log(maternity)
        console.log(duty)

        // var sl = $($($(this)[0]).parent().closest('tr').find('td')[7]).find('select').val();        
        // var ml = $($($(this)[0]).parent().closest('tr').find('td')[8]).find('select').val();
        // var cl = $($($(this)[0]).parent().closest('tr').find('td')[9]).find('select').val();
        // var maternity = $($($(this)[0]).parent().closest('tr').find('td')[10]).find('select').val();
        // var duty = $($($(this)[0]).parent().closest('tr').find('td')[11]).find('select').val();
        var month = $('#monthInput').val();
        var year = $('#yearInput').val();
        // var formSubmit = $($(this).parent().closest('tr').find('input')).is(':checked');
        // console.log(formSubmit);
        if(formSubmit)
            var formSubmitted = 1;
        else
            var formSubmitted = 0;
        var amount;
        var indexRow = $(this).parent().parent().index()

        // console.log();
        $.confirm({
            content: ' This action is permanent.',
            buttons: {
                Activate: {
                    text: 'Proceed',
                    btnClass:'btn-green',
                    action: function () {
                        $.confirm({
                            content: ' This action can\'t be undone.',
                            type:'orange',
                            // theme:'supervan',
                            title:'Do you really want to Proceed ?',
                            icon: 'fa fa-exclamation',
                            buttons: {
                                Activate: {
                                    btnClass:'btn-green',
                                    text: 'Do it Anyway',

                                    action: function () {
                                        
                                        $.ajax({
                                            type:"POST",
                                            url:'./utils/updateRecords.php',
                                            data:{student_id:id, program_name:program_name, absents:absents, presents:presents, sl:sl, ml:ml, cl:cl, maternity:maternity, duty:duty, month:month, year:year},
                                            success:function(data)
                                            {
                                                //data = jQuery.parseJSON(data);
                                                console.log(data);
                                                amount =data;
                                                // $($($('#studentsTable').find('tr')[indexRow+1]).find('td')[13]).text(amount)
                                                $($($('#studentsTable').find('tr')[indexRow+1]).find('td')[15]).empty().text('DONE');
                                                $($($(this)[0]).parent().closest('tr').find('td')[7]).empty().text(sl);
                                                $($($(this)[0]).parent().closest('tr').find('td')[8]).empty().text(ml);
                                                $($($(this)[0]).parent().closest('tr').find('td')[9]).empty().text(cl);
                                                $($($(this)[0]).parent().closest('tr').find('td')[10]).empty().text(maternity);
                                                $($($(this)[0]).parent().closest('tr').find('td')[11]).empty().text(duty);
                                                console.log(typeof(data.charAt(0)));
                                                if(data  == "-1")
                                                    msg = 'errrr in sl';
                                                else if(data == -2)
                                                    msg = 'error in ml';
                                                else if(data == -3)
                                                    msg = 'error in cl';
                                                else if(data == '-4')
                                                    msg = 'error in maternity';
                                                else if(data == -5)
                                                    msg = 'error in duty';
                                                else
                                                    msg=data;

                                                $.alert(msg);
                                            },
                                            statusCode:{
                                                404:function()
                                                {
                                                    $.alert('oops');
                                                }
                                            }
                                        })
                                        
                                    }
                                },
                                Cancel: {
                                    btnClass:'btn-red',
                                    action:function () {
                                        $(this).prop('disabled', true);
                                        $.alert({
                                            title:'ABORTED!',
                                            type:'red',
                                            content:'Operation cancelled by the user.'
                                        })
                                    }
                                }
                            }
                        });
                        
                    }
                },
                Cancel: {
                    btnClass:'btn-red',
                    action:function () {
                        $.alert({
                            title:'ABORTED!',
                            type:'red',
                            content:'No Input from user.'
                        })
                    }
                }               
            }
        });


        
    })
    $(document.body).on('click', '#printReport', function(){
        $('#myModal').modal('toggle');
        $.ajax({
            url:"./utils/getProgramData.php",
            method:"POST",
            data:"",
            DataType:"json",
            success:function(data){
                //json.parseJSON(data);
                //JSON.stringify(data);
                 var json = jQuery.parseJSON( data );
                 console.log(json.length);
                 var x=json.length;
                console.log(data);
                $('#reportProgram').text('');
                for(var i=0;i<x;i++)
                {
                    $('#reportProgram').append('<option>'+json[i]['program_name']+'</option>');
                }
                
        
            }
        })
    })

    $(document).ready( function(){
        console.log("aa");
        $.ajax({
            url:"./utils/checkLogin.php",
            method:"POST",
            data:"",
            DataType:"json",
            success:function(data){
                //json.parseJSON(data);
                //JSON.stringify(data);
                console.log(data);
                 var json = jQuery.parseJSON( data );
                 console.log("Asdsad");
                 console.log(json);
                if(json==0)console.log("zero");

        
            }
        })
    })
    $(document.body).on('click', '#printReportBtn', function(){
        $('#myModal').modal('toggle');

        
        var month= $('#reportMonth').val();
        var year= $('#reportYear').val();
        
        var program = $('#reportProgram').val();

        window.location = "./utils/printRecords.php?month="+month+"&year="+year+"&program_name="+program;

        $.ajax({
            type:'POST',
            url:'./utils/printRecords.php',
            data:{month:month,year:year, program_name:program},
            success:function(data)
            {
                console.log(data);
            }
    
        })
    })

    $(document.body).on('click', '#logoutBtn', function(){
        logout();
    })
        
    $(document.body).on('click', '#logoutBtn1', function(){
        logout();
    })    

    $(document.body).on('click', '#uploadMonthlyCSV', function(){
            
        
            var formData = new FormData ($("#uploadMonthlyData")[0]);
            console.log(formData)
            $.ajax({
                url: "./utils/uploadcsv.php",// chnage location
                type: 'POST',
                data: formData,
                async: false,
                success: function (data) {
                    alert(data)
                },
                cache: false,
                contentType: false,
                processData: false
            });

            

    })
    $(document.body).on('click', '#uploadAllStudentsCsv', function(){
        
            var formData = new FormData ($("#uploadAllStudentsData")[0]);
            console.log(formData)
            $.ajax({
                url: "./utils/uploadmaindata.php",// chnage location
                type: 'POST',
                data: formData,
                async: false,
                success: function (data) {
                    if(data==1)
                    {
                        alert("wrong");
                    }
                    else
                    {
                        alert("right");
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });   

    })
    
    $(document.body).on('change', '#uploadMonthlyDataCsvFile', function(){
        $('#uploadMonthlyDataFileWrapper').attr('data-text', $('#uploadMonthlyDataCsvFile').val())

    })
    $(document.body).on('change', '#uploadAllStudentsContainerCsvFile', function(){
        $('#uploadAllStudentsContainerFileWrapper').attr('data-text', $('#uploadAllStudentsContainerCsvFile').val())

    })    

    
})


