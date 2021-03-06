<div class="preloader flex-column justify-content-center align-items-center" style="background-color: #ffff !important; ">
	<img class="animation__shake" src="../images/Radar.gif" alt="logo" height="60" width="60">
</div>

  <!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top">
    <ul class="navbar-nav">
      	<li class="nav-item">
        	<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      	</li>
      	<li class="nav-item d-none d-sm-inline-block">
        	<a href="./" class="nav-link">Home</a>
      	</li>
      	<li class="nav-item d-none d-sm-inline-block"></li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      	<li class="nav-item">
        	<a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <span id="timeRemaining"></span>
        	</a>
        <div class="navbar-search-block">
          <span id="timeRemaining"></span>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link" href="../signout">
          <i class="fa fa-sign-out-alt"></i>
         
        </a>
        
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fa fa-plus"></i>
  
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="./" class="brand-link">
      <img src="../images/icon2.png" alt="Osabox Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><b>Osabox</b></span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo getStaffMemberImage($connect, $_SESSION['user_id'], $_SESSION['parent_id'])?>" class="img-circle elevation-2" style="height:55px;width: 55px; border-radius: 50%;" alt="User Image">
        </div>
        <div class="info">
          <a href="./" class="d-block"><?php echo getStaffMemberNames($connect, $_SESSION['user_id'], $_SESSION['parent_id'])?></a>
        </div>
      </div>
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search" id="">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php
            if (isset($_COOKIE['SelectedBranch'])) {
              $SelectedBranch = $_COOKIE['SelectedBranch'];
              $branch_id = base64_decode($SelectedBranch);
            ?>
            <li class="nav-item menu-open">
              <a href="./" class="nav-link active">
                <i class="nav-icon fas fa-th"></i>
                <p>
                  <?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?>
                </p>
              </a>
            </li>
          <?php  
            }else{?>
              <li class="nav-item menu-open">
                <a href="branches/branch" class="nav-link active">
                  <i class="nav-icon bi bi-building"></i>
                  <p>
                    Set Up Branch
                  </p>
                </a>
              </li>
       <?php
            }

          ?>

          
          <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Branches
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <?php
                $sql = $connect->prepare("SELECT  * FROM allowed_branches WHERE staff_id = ? AND parent_id = ? ");
                $sql->execute(array($_SESSION['user_id'], $_SESSION['parent_id']));
                if($sql->rowCount() > 0){
                foreach ($sql->fetchAll() as $row) {
                    $branch_id = $row['branch_id'];
                    if ($branch_id == $BRANCHID) {?>
                      <li class="nav-item">
                        <a href="" class="nav-link NavsetCookies" data-id="<?php echo base64_encode($branch_id)?>" id="<?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?>">
                          <i class="bi bi-circle-fill nav-icon text-success"></i>
                          <p><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?></p>
                        </a>
                      </li>
               <?php       
                    }else{?>
                      <li class="nav-item">
                        <a href="" class="nav-link NavsetCookies" data-id="<?php echo base64_encode($branch_id)?>" id="<?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?>">
                          <i class="bi bi-collection nav-icon"></i>
                          <p><?php echo ucwords(getBranchName($connect, $_SESSION['parent_id'], $branch_id))?></p>
                        </a>
                      </li>
              <?php
                    }
                  }
                    
                }else{?>
                  <li class="nav-item">
                        <a href="branches/branch" type="button" class="nav-link" >
                          <i class="bi bi-collection nav-icon"></i>
                          Create Branch
                        </a>

                  </li>
                  
            <?php
                }
            ?>

            </ul>
          </li>
          <!-- Show No Nav Till Branch is Selected -->
          <?php if (isset($_COOKIE['SelectedBranch'])):?>
          <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Borrowers
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="borrowers/all-borrowers" class="nav-link">
                  <i class="bi bi-person-circle text-success nav-icon"></i>
                  <p>View Borrowers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="borrowers/add-new-borrower" class="nav-link">
                  <i class="bi bi-plus-circle text-danger nav-icon"></i>
                  <p>Add Borrowers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="borrowers/add-new-group-borrowers" class="nav-link">
                  <i class="bi bi-bounding-box-circles nav-icon"></i>
                  <p>Group Borrowers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="borrowers/add_guarantors" class="nav-link">
                  <i class="bi bi-circle-half nav-icon"></i>
                  <p>Add Guarantor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="borrowers/view_guarantors" class="nav-link">
                  <i class="bi bi-circle text-primary nav-icon"></i>
                  <p>View Guarantor</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="borrowers/sms_borrower" class="nav-link">
                  <i class="bi bi-reply-all nav-icon"></i>
                  <p>SMS Borrowers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="borrowers/email_borrower" class="nav-link">
                  <i class="bi bi-mailbox nav-icon"></i>
                  <p>Email Borrowers</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Loans
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="loans/loan_settings" class="nav-link">
                  <i class="bi bi-gear-wide-connected nav-icon"></i>
                  <p>Loan Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="loans/create-new-loan.php" class="nav-link">
                  <i class="bi bi-plus-square nav-icon"></i>
                  <p>Loans Add  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="loans/view_loans" class="nav-link">
                  <i class="bi bi-arrow-right-square nav-icon"></i>
                  <p>Loans View </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="loans/due_loans" class="nav-link">
                  <i class="bi bi-piggy-bank nav-icon"></i>
                  <p>Loans Due </p>
                </a>
              </li>             
              
              <li class="nav-item">
                <a href="loans/disbursed_loans" class="nav-link">
                  <i class="bi bi-binoculars nav-icon"></i>
                  <p>Loans Disbursed </p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="loans/loan_charts" class="nav-link">
                  <i class="bi bi-pie-chart nav-icon"></i>
                  <p>Loan Chart</p>
                </a>
              </li> -->
            </ul>
          </li>
          <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon bi bi-bank"></i>
              <p>
                Investors
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="investors/add_investor" class="nav-link">
                  <i class="bi bi-plus-circle nav-icon"></i>
                  <p>Add & View Investor</p>
                </a>
              </li>
              <!-- <li class="nav-item">
                <a href="investors/sms_investor" class="nav-link">
                  <i class="bi bi-circle nav-icon"></i>
                  <p>SMS Investors</p>
                </a>
              </li> -->
              <li class="nav-item">
                <a href="investors/email_investor" class="nav-link">
                  <i class="bi bi-circle text-primary nav-icon"></i>
                  <p>Email Investors</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">Extras</li>
          
          
          <li class="nav-item">
              <a href="projects/to-do-list" class="nav-link">
                <i class="bi bi-list-check nav-icon"></i>
                <p>Todo List </p>
              </a>
          </li>
          <!-- <li class="nav-item">
            <a href="projects/kanban" class="nav-link">
              <i class="nav-icon fas fa-columns"></i>
              <p>
                Kanban Board
              </p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon bi bi-archive"></i>
              <p>
                Projects
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              
              <li class="nav-item">
                <a href="projects/project-add" class="nav-link">
                  <i class="bi bi-bag-plus nav-icon"></i>
                  <p>Projects Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="projects/projects" class="nav-link">
                  <i class="bi bi-binoculars nav-icon"></i>
                  <p>Projects View</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="projects/projects-completed" class="nav-link">
                  <i class="bi bi-arrow-bar-right nav-icon"></i>
                  <p>Projects Completed</p>
                </a>
              </li>
            </ul>
          </li>
              
          <?php if($_SESSION['user_role'] == 'Admin'):?>

            <li class="nav-header border-top pb-3">Admins</li>
              <li class="nav-item">
              <a href="expenses/add_expenses" class="nav-link">
                <i class="bi bi-plus-square nav-icon"></i>
                <p>Income $ Expenses </p>
              </a>
            </li>
            <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon bi bi-wallet"></i>
              <p>
                Payroll
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="payroll/payroll_settings" class="nav-link">
                  <i class="bi bi-coin nav-icon"></i>
                  <p>Payroll Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="payroll/create-payroll" class="nav-link">
                  <i class="bi bi-coin nav-icon"></i>
                  <p>Payroll Create</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="payroll/check-payroll" class="nav-link">
                  <i class="bi bi-coin nav-icon"></i>
                  <p>Payroll View </p>
                </a>
              </li>
            </ul>
          </li>
            <li class="nav-item">
            <a href="navtree" class="nav-link">
              <i class="nav-icon bi bi-wallet"></i>
              <p>
                Admin Controls
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-header text-danger border-bottom">Settings</li>
              <li class="nav-item">
                <a href="members/email-settings" class="nav-link text-danger">
                  <i class="bi bi-mailbox nav-icon"></i>
                  <p>Email Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/sms-create-sender-id" class="nav-link text-danger">
                  <i class="bi bi-reply-all nav-icon"></i>
                  <p>SMS Settings</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="members/positions" class="nav-link text-danger">
                  <i class="bi bi-plus-square nav-icon"></i>
                  <p>Create Positions</p>
                </a>
              </li>
              <li class="nav-header text-success border-bottom">Staff Care</li>
              <li class="nav-item">
                <a href="members/add_admin" class="nav-link  text-success">
                  <i class="bi bi-person-plus nav-icon"></i>
                  <p>Add Staff</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/staff-members" class="nav-link  text-success">
                  <i class="bi bi-arrow-right-square nav-icon"></i>
                  <p>View Staff</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/email-staff" class="nav-link  text-success">
                  <i class="bi bi-mailbox nav-icon"></i>
                  <p>Email Staff</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/sms-staff" class="nav-link text-success">
                  <i class="bi bi-reply-all nav-icon"></i>
                  <p>SMS Staff</p>
                </a>
              </li>

              <li class="nav-header text-primary border-bottom">Management</li>
              <li class="nav-item">
                <a href="members/branches" class="nav-link">
                  <i class="bi bi-building nav-icon"></i>
                  <p>Branches</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="members/all_income" class="nav-link">
                  <i class="bi bi-coin nav-icon"></i>
                  <p>All Income</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="members/login_data" class="nav-link">
                  <i class="bi-clock-history nav-icon"></i>
                  <p>Logs</p>
                </a>
              </li>
            </ul>
          </li>
          
          <?php else:?>

          <?php endif;?>
        <?php else:?>

        <?php endif;?>
          <li class="nav-item">
            <a href="../signout" class="nav-link">
              <i class="nav-icon fa fa-sign-out-alt text-danger"></i>
              <p class="text">Sign Out</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
</aside>