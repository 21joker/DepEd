<div class="register-box">
    <div class="register-logo">
        <a href=""></a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Register an account</p>
            <?= $this->Form->create(null, ['type' => 'file']) ?>
            <div class="form-group mb-3">
                <?= $this->Form->control('id_number', ['class' => 'form-control', 'placeholder' => 'ID Number', 'required' => true]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('username', ['class' => 'form-control', 'placeholder' => 'Username', 'required' => true]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('first_name', ['class' => 'form-control', 'placeholder' => 'First Name', 'required' => true]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('middle_initial', [
                    'class' => 'form-control',
                    'placeholder' => 'Middle Innitial',
                    'label' => 'Middle Innitial',
                    'maxlength' => 2,
                    'pattern' => '[A-Za-z]\\.?',
                    'required' => true
                ]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('last_name', ['class' => 'form-control', 'placeholder' => 'Last name', 'required' => true]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('suffix', [
                    'type' => 'select',
                    'class' => 'form-control',
                    'empty' => 'Select Suffix',
                    'options' => [
                        'Jr.' => 'Jr.',
                        'Sr.' => 'Sr.',
                        'II' => 'II',
                        'III' => 'III',
                        'IV' => 'IV',
                        'V' => 'V',
                        'VI' => 'VI',
                        'VIII' => 'VIII',
                        'IX' => 'IX',
                        'X' => 'X',
                    ],
                ]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('degree', ['class' => 'form-control', 'placeholder' => 'Degree']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('rank', ['class' => 'form-control', 'placeholder' => 'Rank']) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('position', [
                    'type' => 'select',
                    'class' => 'form-control',
                    'empty' => 'Select Position',
                    'options' => [
                        'ACCOUNTANT I' => 'ACCOUNTANT I',
                        'ACCOUNTANT II' => 'ACCOUNTANT II',
                        'ACCOUNTANT III' => 'ACCOUNTANT III',
                        'ACCOUNTANT IV' => 'ACCOUNTANT IV',
                        'ACCOUNTING CLERK II' => 'ACCOUNTING CLERK II',
                        'ADMINISTRATIVE AIDE I' => 'ADMINISTRATIVE AIDE I',
                        'ADMINISTRATIVE AIDE II' => 'ADMINISTRATIVE AIDE II',
                        'ADMINISTRATIVE AIDE III' => 'ADMINISTRATIVE AIDE III',
                        'ADMINISTRATIVE AIDE IV' => 'ADMINISTRATIVE AIDE IV',
                        'ADMINISTRATIVE AIDE V' => 'ADMINISTRATIVE AIDE V',
                        'ADMINISTRATIVE AIDE VI' => 'ADMINISTRATIVE AIDE VI',
                        'ADMINISTRATIVE ASSISTANT I' => 'ADMINISTRATIVE ASSISTANT I',
                        'ADMINISTRATIVE ASSISTANT II' => 'ADMINISTRATIVE ASSISTANT II',
                        'ADMINISTRATIVE ASSISTANT III' => 'ADMINISTRATIVE ASSISTANT III',
                        'ADMINISTRATIVE ASSISTANT IV' => 'ADMINISTRATIVE ASSISTANT IV',
                        'ADMINISTRATIVE ASSISTANT V' => 'ADMINISTRATIVE ASSISTANT V',
                        'ADMINISTRATIVE ASSISTANT VI' => 'ADMINISTRATIVE ASSISTANT VI',
                        'ADMINISTRATIVE OFFICER I' => 'ADMINISTRATIVE OFFICER I',
                        'ADMINISTRATIVE OFFICER II' => 'ADMINISTRATIVE OFFICER II',
                        'ADMINISTRATIVE OFFICER III' => 'ADMINISTRATIVE OFFICER III',
                        'ADMINISTRATIVE OFFICER IV' => 'ADMINISTRATIVE OFFICER IV',
                        'ADMINISTRATIVE OFFICER V' => 'ADMINISTRATIVE OFFICER V',
                        'AGRICULTURALIST I' => 'AGRICULTURALIST I',
                        'AGRICULTURALIST II' => 'AGRICULTURALIST II',
                        'AGRICULTURAL TECHNICIAN II' => 'AGRICULTURAL TECHNICIAN II',
                        'AQUACULTURIST I' => 'AQUACULTURIST I',
                        'ARCHITECT III' => 'ARCHITECT III',
                        'ARTIST-ILLUSTRATOR II' => 'ARTIST-ILLUSTRATOR II',
                        'ASSISTANT PROFESSOR II' => 'ASSISTANT PROFESSOR II',
                        'ASSISTANT SCHOOL PRINCIPAL I' => 'ASSISTANT SCHOOL PRINCIPAL I',
                        'ASSISTANT SCHOOL PRINCIPAL II' => 'ASSISTANT SCHOOL PRINCIPAL II',
                        'ASSISTANT SCHOOLS DIVISION SUPERINTENDENT' => 'ASSISTANT SCHOOLS DIVISION SUPERINTENDENT',
                        'ASSISTANT SPECIAL SCHOOL PRINCIPAL' => 'ASSISTANT SPECIAL SCHOOL PRINCIPAL',
                        'ASSISTANT TEACHER\'S CAMP SUPERINTENDENT' => 'ASSISTANT TEACHER\'S CAMP SUPERINTENDENT',
                        'ATTORNEY II' => 'ATTORNEY II',
                        'ATTORNEY III' => 'ATTORNEY III',
                        'ATTORNEY IV' => 'ATTORNEY IV',
                        'ATTORNEY V' => 'ATTORNEY V',
                        'ATTORNEY VI' => 'ATTORNEY VI',
                        'BOOKKEEPER' => 'BOOKKEEPER',
                        'CASHIER I' => 'CASHIER I',
                        'CHIEF ACCOUNTANT' => 'CHIEF ACCOUNTANT',
                        'CHIEF ADMINISTRATIVE OFFICER' => 'CHIEF ADMINISTRATIVE OFFICER',
                        'CHIEF EDUCATION PROGRAM  SPECIALIST' => 'CHIEF EDUCATION PROGRAM  SPECIALIST',
                        'CHIEF EDUCATION SUPERVISOR' => 'CHIEF EDUCATION SUPERVISOR',
                        'CHIEF HEALTH PROGRAM OFFICER' => 'CHIEF HEALTH PROGRAM OFFICER',
                        'CINEMATOGRAPHER I' => 'CINEMATOGRAPHER I',
                        'CLERK II' => 'CLERK II',
                        'CLERK III' => 'CLERK III',
                        'COLLEGE LIBRARIAN I' => 'COLLEGE LIBRARIAN I',
                        'COLLEGE LIBRARIAN II' => 'COLLEGE LIBRARIAN II',
                        'COMPUTER FILE LIBRARIAN I' => 'COMPUTER FILE LIBRARIAN I',
                        'COMPUTER MAINTENANCE TECHNOLOGIST I' => 'COMPUTER MAINTENANCE TECHNOLOGIST I',
                        'COMPUTER MAINTENANCE TECHNOLOGIST II' => 'COMPUTER MAINTENANCE TECHNOLOGIST II',
                        'COMPUTER PROGRAMMER II' => 'COMPUTER PROGRAMMER II',
                        'COMPUTER PROGRAMMER III' => 'COMPUTER PROGRAMMER III',
                        'COOK I' => 'COOK I',
                        'COPY READER' => 'COPY READER',
                        'COXSWAIN' => 'COXSWAIN',
                        'CRAFTS EDUCATION DEMONSTRATOR I' => 'CRAFTS EDUCATION DEMONSTRATOR I',
                        'CRAFTS EDUCATION DEMONSTRATOR II' => 'CRAFTS EDUCATION DEMONSTRATOR II',
                        'CREATIVE ART SPECIALIST II' => 'CREATIVE ART SPECIALIST II',
                        'DENTAL AIDE' => 'DENTAL AIDE',
                        'DENTIST I' => 'DENTIST I',
                        'DENTIST II' => 'DENTIST II',
                        'DENTIST III' => 'DENTIST III',
                        'DEPARTMENT ASSISTANT SECRETARY' => 'DEPARTMENT ASSISTANT SECRETARY',
                        'DEPARTMENT SECRETARY' => 'DEPARTMENT SECRETARY',
                        'DEPARTMENT UNDERSECRETARY' => 'DEPARTMENT UNDERSECRETARY',
                        'DIRECTOR III' => 'DIRECTOR III',
                        'DIRECTOR IV' => 'DIRECTOR IV',
                        'DISBURSING OFFICER I' => 'DISBURSING OFFICER I',
                        'DISBURSING OFFICER II' => 'DISBURSING OFFICER II',
                        'DORMITORY MANAGER I' => 'DORMITORY MANAGER I',
                        'DORMITORY MANAGER II' => 'DORMITORY MANAGER II',
                        'DRAFTSMAN II' => 'DRAFTSMAN II',
                        'DRIVER I' => 'DRIVER I',
                        'EDUCATION PROGRAM SPECIALIST I' => 'EDUCATION PROGRAM SPECIALIST I',
                        'EDUCATION PROGRAM SPECIALIST II' => 'EDUCATION PROGRAM SPECIALIST II',
                        'EDUCATION PROGRAM SUPERVISOR' => 'EDUCATION PROGRAM SUPERVISOR',
                        'ELECTRONICS AND COMMUNICATION' => 'ELECTRONICS AND COMMUNICATION',
                        'ENGINEER II' => 'ENGINEER II',
                        'ENGINEER III' => 'ENGINEER III',
                        'ENGINEER IV' => 'ENGINEER IV',
                        'ENGINEER V' => 'ENGINEER V',
                        'EXECUTIVE ASSISTANT I' => 'EXECUTIVE ASSISTANT I',
                        'EXECUTIVE ASSISTANT II' => 'EXECUTIVE ASSISTANT II',
                        'EXECUTIVE ASSISTANT III' => 'EXECUTIVE ASSISTANT III',
                        'EXECUTIVE ASSISTANT IV' => 'EXECUTIVE ASSISTANT IV',
                        'EXECUTIVE ASSISTANT V' => 'EXECUTIVE ASSISTANT V',
                        'EXECUTIVE  DIRECTOR II' => 'EXECUTIVE  DIRECTOR II',
                        'FARM WORKER I' => 'FARM WORKER I',
                        'FISCAL EXAMINER I' => 'FISCAL EXAMINER I',
                        'FISHERMAN' => 'FISHERMAN',
                        'GUESTHOUSE CARETAKER' => 'GUESTHOUSE CARETAKER',
                        'GUIDANCE COORDINATOR I' => 'GUIDANCE COORDINATOR I',
                        'GUIDANCE COORDINATOR II' => 'GUIDANCE COORDINATOR II',
                        'GUIDANCE COORDINATOR III' => 'GUIDANCE COORDINATOR III',
                        'GUIDANCE COUNSELOR I' => 'GUIDANCE COUNSELOR I',
                        'GUIDANCE COUNSELOR II' => 'GUIDANCE COUNSELOR II',
                        'GUIDANCE COUNSELOR III' => 'GUIDANCE COUNSELOR III',
                        'GUIDANCE SERVICE SPECIALIST I' => 'GUIDANCE SERVICE SPECIALIST I',
                        'GUIDANCE SERVICE SPECIALIST II' => 'GUIDANCE SERVICE SPECIALIST II',
                        'HANDICRAFT WORKER I' => 'HANDICRAFT WORKER I',
                        'HANDICRAFT WORKER II' => 'HANDICRAFT WORKER II',
                        'HEAD EXECUTIVE ASSISTANT' => 'HEAD EXECUTIVE ASSISTANT',
                        'HEAD TEACHER I' => 'HEAD TEACHER I',
                        'HEAD TEACHER II' => 'HEAD TEACHER II',
                        'HEAD TEACHER III' => 'HEAD TEACHER III',
                        'HEAD TEACHER IV' => 'HEAD TEACHER IV',
                        'HEAD TEACHER V' => 'HEAD TEACHER V',
                        'HEAD TEACHER VI' => 'HEAD TEACHER VI',
                        'HEALTH EDUCATION AND PROMOTION I' => 'HEALTH EDUCATION AND PROMOTION I',
                        'HEALTH EDUCATION AND PROMOTION II' => 'HEALTH EDUCATION AND PROMOTION II',
                        'HEAVY EQUIPMENT OPERATOR I' => 'HEAVY EQUIPMENT OPERATOR I',
                        'HOUSEPARENT I' => 'HOUSEPARENT I',
                        'HUMAN RESOURCE MANAGEMENT' => 'HUMAN RESOURCE MANAGEMENT',
                        'INFORMATION SYSTEMS ANALYST II' => 'INFORMATION SYSTEMS ANALYST II',
                        'INFORMATION SYSTEMS ANALYST III' => 'INFORMATION SYSTEMS ANALYST III',
                        'INFORMATION SYSTEMS RESEARCHER' => 'INFORMATION SYSTEMS RESEARCHER',
                        'INFORMATION TECHNOLOGY OFFICER I' => 'INFORMATION TECHNOLOGY OFFICER I',
                        'INFORMATION TECHNOLOGY OFFICER II' => 'INFORMATION TECHNOLOGY OFFICER II',
                        'INFORMATION TECHNOLOGY OFFICER III' => 'INFORMATION TECHNOLOGY OFFICER III',
                        'INSTRUCTOR I' => 'INSTRUCTOR I',
                        'INSTRUCTOR II' => 'INSTRUCTOR II',
                        'INTERNAL AUDITING ASSISTANT' => 'INTERNAL AUDITING ASSISTANT',
                        'INTERNAL AUDITOR I' => 'INTERNAL AUDITOR I',
                        'INTERNAL AUDITOR II' => 'INTERNAL AUDITOR II',
                        'INTERNAL AUDITOR III' => 'INTERNAL AUDITOR III',
                        'INTERNAL AUDITOR IV' => 'INTERNAL AUDITOR IV',
                        'INTERNAL AUDITOR V' => 'INTERNAL AUDITOR V',
                        'LABORATORY TECHNICIAN I' => 'LABORATORY TECHNICIAN I',
                        'LEGAL AIDE' => 'LEGAL AIDE',
                        'LEGAL ASSISTANT I' => 'LEGAL ASSISTANT I',
                        'LEGAL ASSISTANT II' => 'LEGAL ASSISTANT II',
                        'LIBRARIAN I' => 'LIBRARIAN I',
                        'LIBRARIAN II' => 'LIBRARIAN II',
                        'LIBRARIAN III' => 'LIBRARIAN III',
                        'LIGHT EQUIPMENT OPERATOR' => 'LIGHT EQUIPMENT OPERATOR',
                        'LINEMEN I' => 'LINEMEN I',
                        'MARINE ENGINEMAN I' => 'MARINE ENGINEMAN I',
                        'MASTER FISHERMAN I' => 'MASTER FISHERMAN I',
                        'MASTER TEACHER I' => 'MASTER TEACHER I',
                        'MASTER TEACHER II' => 'MASTER TEACHER II',
                        'MASTER TEACHER III' => 'MASTER TEACHER III',
                        'MECHANIC I' => 'MECHANIC I',
                        'MECHANICAL PLANT OPERATOR I' => 'MECHANICAL PLANT OPERATOR I',
                        'MECHANICAL PLANT OPERATOR II' => 'MECHANICAL PLANT OPERATOR II',
                        'MEDICAL OFFICER III' => 'MEDICAL OFFICER III',
                        'MEDICAL OFFICER IV' => 'MEDICAL OFFICER IV',
                        'METAL WORKER I' => 'METAL WORKER I',
                        'NURSE I' => 'NURSE I',
                        'NURSE II' => 'NURSE II',
                        'NURSE MAID I' => 'NURSE MAID I',
                        'NUTRITIONIST-DIETITIAN I' => 'NUTRITIONIST-DIETITIAN I',
                        'NUTRITIONIST-DIETITIAN II' => 'NUTRITIONIST-DIETITIAN II',
                        'NUTRITIONIST-DIETITIAN III' => 'NUTRITIONIST-DIETITIAN III',
                        'PHOTOENGRAVER II' => 'PHOTOENGRAVER II',
                        'PLANNING OFFICER II' => 'PLANNING OFFICER II',
                        'PLANNING OFFICER III' => 'PLANNING OFFICER III',
                        'PLANNING OFFICER IV' => 'PLANNING OFFICER IV',
                        'PLANNING OFFICER V' => 'PLANNING OFFICER V',
                        'PRINTING FOREMAN' => 'PRINTING FOREMAN',
                        'PROJECT DEVELOPMENT ASSISTANT' => 'PROJECT DEVELOPMENT ASSISTANT',
                        'PROJECT DEVELOPMENT OFFICER I' => 'PROJECT DEVELOPMENT OFFICER I',
                        'PROJECT DEVELOPMENT OFFICER II' => 'PROJECT DEVELOPMENT OFFICER II',
                        'PROJECT DEVELOPMENT OFFICER III' => 'PROJECT DEVELOPMENT OFFICER III',
                        'PROJECT DEVELOPMENT OFFICER IV' => 'PROJECT DEVELOPMENT OFFICER IV',
                        'PROJECT DEVELOPMENT OFFICER V' => 'PROJECT DEVELOPMENT OFFICER V',
                        'PROOFREADER II' => 'PROOFREADER II',
                        'PSYCHOLOGIST I' => 'PSYCHOLOGIST I',
                        'PUBLIC SCHOOLS DISTRICT SUPERVISOR' => 'PUBLIC SCHOOLS DISTRICT SUPERVISOR',
                        'RECORDS OFFICER II' => 'RECORDS OFFICER II',
                        'REGISTRAR I' => 'REGISTRAR I',
                        'REGISTRAR II' => 'REGISTRAR II',
                        'REPRODUCTION MACHINE OPERATOR' => 'REPRODUCTION MACHINE OPERATOR',
                        'SCHOOL FARM DEMONSTRATOR' => 'SCHOOL FARM DEMONSTRATOR',
                        'SCHOOL FARMING COORDINATOR I' => 'SCHOOL FARMING COORDINATOR I',
                        'SCHOOL FARMING COORDINATOR II' => 'SCHOOL FARMING COORDINATOR II',
                        'SCHOOL FARMING COORDINATOR III' => 'SCHOOL FARMING COORDINATOR III',
                        'SCHOOL LIBRARIAN I' => 'SCHOOL LIBRARIAN I',
                        'SCHOOL LIBRARIAN II' => 'SCHOOL LIBRARIAN II',
                        'SCHOOL LIBRARIAN III' => 'SCHOOL LIBRARIAN III',
                        'SCHOOL PRINCIPAL I' => 'SCHOOL PRINCIPAL I',
                        'SCHOOLPRINCIPAL II' => 'SCHOOLPRINCIPAL II',
                        'SCHOOL PRINCIPAL III' => 'SCHOOL PRINCIPAL III',
                        'SCHOOL PRINCIPAL IV' => 'SCHOOL PRINCIPAL IV',
                        'SCHOOLS DIVISION SUPERINTENDENT' => 'SCHOOLS DIVISION SUPERINTENDENT',
                        'SCIENCE RESEARCH SPECIALIST II' => 'SCIENCE RESEARCH SPECIALIST II',
                        'SCIENCE RESEARCH TECHNICIAN I' => 'SCIENCE RESEARCH TECHNICIAN I',
                        'SCIENCE RESEARCH TECHNICIAN II' => 'SCIENCE RESEARCH TECHNICIAN II',
                        'SCIENCE RESEARCH TECHNICIAN III' => 'SCIENCE RESEARCH TECHNICIAN III',
                        'SCIENCE RESEARCH TECHNICIAN IV' => 'SCIENCE RESEARCH TECHNICIAN IV',
                        'SECURITY GUARD I' => 'SECURITY GUARD I',
                        'SECURITY GUARD II' => 'SECURITY GUARD II',
                        'SECURITY GUARD III' => 'SECURITY GUARD III',
                        'SECURITY OFFICER II' => 'SECURITY OFFICER II',
                        'SECURITY OFFICER IV' => 'SECURITY OFFICER IV',
                        'SENIOR ADMINISTRATIVE ASSISTANT I' => 'SENIOR ADMINISTRATIVE ASSISTANT I',
                        'SENIOR ADMINISTRATIVE ASSISTANT II' => 'SENIOR ADMINISTRATIVE ASSISTANT II',
                        'SENIOR ADMINISTRATIVE ASSISTANT III' => 'SENIOR ADMINISTRATIVE ASSISTANT III',
                        'SENIOR ADMINISTRATIVE ASSISTANT IV' => 'SENIOR ADMINISTRATIVE ASSISTANT IV',
                        'SENIOR ADMINISTRATIVE ASSISTANT V' => 'SENIOR ADMINISTRATIVE ASSISTANT V',
                        'SENIOR BOOKKEEPER' => 'SENIOR BOOKKEEPER',
                        'SENIOR EDUCATION PROGRAM SPECIALIST' => 'SENIOR EDUCATION PROGRAM SPECIALIST',
                        'SENIOR SCIENCE RESEARCH SPECIALIST' => 'SENIOR SCIENCE RESEARCH SPECIALIST',
                        'SOCIAL WELFARE OFFICER I' => 'SOCIAL WELFARE OFFICER I',
                        'SPECIAL EDUCATION TEACHER I' => 'SPECIAL EDUCATION TEACHER I',
                        'SPECIAL EDUCATION TEACHER II' => 'SPECIAL EDUCATION TEACHER II',
                        'SPECIAL EDUCATION TEACHER III' => 'SPECIAL EDUCATION TEACHER III',
                        'SPECIAL EDUCATION TEACHER IV' => 'SPECIAL EDUCATION TEACHER IV',
                        'SPECIAL EDUCATION TEACHER V' => 'SPECIAL EDUCATION TEACHER V',
                        'SPECIAL INVESTIGATOR II' => 'SPECIAL INVESTIGATOR II',
                        'SPECIAL INVESTIGATOR III' => 'SPECIAL INVESTIGATOR III',
                        'SPECIAL SCHOOL PRINCIPAL I' => 'SPECIAL SCHOOL PRINCIPAL I',
                        'SPECIAL SCHOOL PRINCIPAL II' => 'SPECIAL SCHOOL PRINCIPAL II',
                        'SPECIAL SCIENCE TEACHER I' => 'SPECIAL SCIENCE TEACHER I',
                        'STATISTICIAN AIDE' => 'STATISTICIAN AIDE',
                        'STATISTICIAN I' => 'STATISTICIAN I',
                        'STATISTICIAN II' => 'STATISTICIAN II',
                        'SUPERVISING ADMINISTRATIVE OFFICER' => 'SUPERVISING ADMINISTRATIVE OFFICER',
                        'SUPERVISING EDUCATION PROGRAM SPECIALIST' => 'SUPERVISING EDUCATION PROGRAM SPECIALIST',
                        'SUPERVISING HEALTH PROGRAM OFFICER' => 'SUPERVISING HEALTH PROGRAM OFFICER',
                        'SUPPLY OFFICER I' => 'SUPPLY OFFICER I',
                        'TEACHER CREDENTIALS EVALUATOR I' => 'TEACHER CREDENTIALS EVALUATOR I',
                        'TEACHER CREDENTIALS EVALUATOR II' => 'TEACHER CREDENTIALS EVALUATOR II',
                        'TEACHER I' => 'TEACHER I',
                        'TEACHER II' => 'TEACHER II',
                        'TEACHER III' => 'TEACHER III',
                        'TEACHERS\' CAMP SUPERINTENDENT' => 'TEACHERS\' CAMP SUPERINTENDENT',
                        'TEACHING-AIDS SPECIALIST' => 'TEACHING-AIDS SPECIALIST',
                        'TELEGRAM CARRIER' => 'TELEGRAM CARRIER',
                        'TYPESETTER II' => 'TYPESETTER II',
                        'UTITLITY FOREMAN' => 'UTITLITY FOREMAN',
                        'UTILITY WORKER I' => 'UTILITY WORKER I',
                        'VOCATIONAL SCHOOL ADMINISTRATOR I' => 'VOCATIONAL SCHOOL ADMINISTRATOR I',
                        'VOCATIONAL SCHOOL ADMINISTRATOR II' => 'VOCATIONAL SCHOOL ADMINISTRATOR II',
                        'WAREHOUSEMAN III' => 'WAREHOUSEMAN III',
                        'WATCHMAN I' => 'WATCHMAN I',
                        'WATCHMAN II' => 'WATCHMAN II',
                        'OTHERS' => 'OTHERS',
                    ],
                ]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('email_address', ['class' => 'form-control', 'placeholder' => 'Email Address', 'required' => true]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('office', [
                    'class' => 'form-control',
                    'empty' => 'Select Office',
                    'options' => [
                        'SGOD' => 'SGOD',
                        'CID' => 'CID',
                        'OSDS' => 'OSDS',
                    ],
                    'required' => true
                ]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('section_unit', [
                    'type' => 'select',
                    'class' => 'form-control',
                    'id' => 'section-unit-select',
                    'empty' => 'Select Section/Unit',
                    'options' => [
                        'OSDS' => 'OSDS',
                        'ICTU' => 'ICTU',
                        'RECORDS UNIT' => 'RECORDS UNIT',
                        'HUMAN AND MANAGEMENT UNIT' => 'HUMAN AND MANAGEMENT UNIT',
                        'PROPERTY AND SUPPLY UNIT' => 'PROPERTY AND SUPPLY UNIT',
                        'PAYROLL UNIT' => 'PAYROLL UNIT',
                        'CASH UNIT' => 'CASH UNIT',
                        'GENERAL SERVICES UNIT' => 'GENERAL SERVICES UNIT',
                        'ALTERNATIVE LEARNING SYSTEM' => 'ALTERNATIVE LEARNING SYSTEM',
                        'CURRICULUM IMPLEMENTATION' => 'CURRICULUM IMPLEMENTATION',
                        'LEARING RESOURCE MANANGEMENT' => 'LEARING RESOURCE MANANGEMENT',
                        'SCHOOL HEALTH AND NUTRITION UNIT' => 'SCHOOL HEALTH AND NUTRITION UNIT',
                        'PLANNING AND RESEARCH UNIT' => 'PLANNING AND RESEARCH UNIT',
                        'EDUCATION FACILITIES UNIT' => 'EDUCATION FACILITIES UNIT',
                        'DRRM' => 'DRRM',
                        'ACCOUNTING' => 'ACCOUNTING',
                        'OTHERS' => 'OTHERS',
                    ],
                    'required' => true,
                ]) ?>
            </div>
            <div class="form-group mb-3" id="section-unit-other-wrap" style="display:none;">
                <?= $this->Form->control('section_unit_other', [
                    'class' => 'form-control',
                    'label' => 'Section/Unit (Others)',
                    'placeholder' => 'Type Section/Unit',
                    'id' => 'section-unit-other',
                    'required' => false,
                ]) ?>
            </div>
            <div class="form-group mb-3">
                <?= $this->Form->control('esignature', [
                    'type' => 'file',
                    'class' => 'form-control',
                    'accept' => '.png,.jpg,.jpeg',
                    'label' => 'Esignature',
                    'required' => true
                ]) ?>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 text-center">
                    <a class="btn btn-link p-0" href="<?= $this->Url->build(['action' => 'login']) ?>">
                        Already have an account?
                    </a>
                </div>
            </div>
            <?= $this->Form->end() ?>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var select = document.getElementById('section-unit-select');
    var otherWrap = document.getElementById('section-unit-other-wrap');
    var otherInput = document.getElementById('section-unit-other');
    if (!select || !otherWrap || !otherInput) {
        return;
    }

    function toggleOther() {
        var isOther = String(select.value || '').toUpperCase() === 'OTHERS';
        otherWrap.style.display = isOther ? '' : 'none';
        otherInput.required = isOther;
        if (!isOther) {
            otherInput.value = '';
        }
    }

    select.addEventListener('change', toggleOther);

    var form = select.form;
    if (form) {
        form.addEventListener('submit', function () {
            var isOther = String(select.value || '').toUpperCase() === 'OTHERS';
            var typed = (otherInput.value || '').trim();
            if (isOther && typed !== '') {
                var opt = Array.prototype.find.call(select.options, function (o) {
                    return o.value === typed;
                });
                if (!opt) {
                    opt = new Option(typed, typed, true, true);
                    select.add(opt);
                }
                select.value = typed;
            }
        });
    }

    toggleOther();
});
</script>

<?php if (!empty($showCredentials)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var username = <?= json_encode($savedUsername ?? '') ?>;
    var password = <?= json_encode($savedPassword ?? '123') ?>;
    var modal = document.getElementById('register-alert-modal');
    var message = document.getElementById('register-alert-message');
    if (!modal || !message) {
        return;
    }
    var html = '<div><strong>Account Created Successful</strong></div>' +
        '<div style="margin-top:8px;">Username: ' + String(username) + '</div>' +
        '<div>Password: ' + String(password) + '</div>';
    message.innerHTML = html;
    modal.setAttribute('data-redirect', <?= json_encode($this->Url->build(['action' => 'login'])) ?>);
    modal.classList.add('show');
});
</script>
<?php endif; ?>

<?php if (!empty($saveErrorMessage)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('register-alert-modal');
    var message = document.getElementById('register-alert-message');
    if (!modal || !message) {
        return;
    }
    var baseMessage = <?= json_encode($saveErrorMessage) ?>;
    var details = <?= json_encode($saveErrorDetails ?? []) ?>;
    var html = '<div>' + baseMessage + '</div>';
    if (details && details.length) {
        html += '<ul style="margin:8px 0 0 18px;">';
        details.forEach(function (item) {
            html += '<li>' + String(item) + '</li>';
        });
        html += '</ul>';
    }
    message.innerHTML = html;
    modal.classList.add('show');
});
</script>
<?php endif; ?>

<div id="register-alert-modal" class="register-alert-modal" aria-hidden="true">
    <div class="register-alert-backdrop"></div>
    <div class="register-alert-dialog" role="dialog" aria-modal="true" aria-labelledby="register-alert-title">
        <div class="register-alert-header">
            <h5 id="register-alert-title">Notice</h5>
            <button type="button" class="register-alert-close" aria-label="Close">×</button>
        </div>
        <div class="register-alert-body" id="register-alert-message"></div>
        <div class="register-alert-footer">
            <button type="button" class="btn btn-primary register-alert-ok">OK</button>
        </div>
    </div>
</div>

<style>
.register-alert-modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}
.register-alert-modal.show {
    display: flex;
}
.register-alert-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
}
.register-alert-dialog {
    position: relative;
    background: #fff;
    border-radius: 10px;
    width: min(420px, 92vw);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
    z-index: 1;
    overflow: hidden;
}
.register-alert-header {
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e9ecef;
}
.register-alert-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}
.register-alert-close {
    background: transparent;
    border: 0;
    font-size: 20px;
    line-height: 1;
    color: #6c757d;
    cursor: pointer;
}
.register-alert-body {
    padding: 16px;
    color: #343a40;
}
.register-alert-footer {
    padding: 12px 16px;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('register-alert-modal');
    if (!modal) {
        return;
    }
    var closeButtons = modal.querySelectorAll('.register-alert-close, .register-alert-ok, .register-alert-backdrop');
    closeButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            modal.classList.remove('show');
            var redirectUrl = modal.getAttribute('data-redirect');
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var input = document.querySelector('input[name="middle_initial"]');
    if (!input) {
        return;
    }
    input.addEventListener('input', function () {
        var letter = (input.value || '').replace(/[^a-zA-Z]/g, '').charAt(0);
        input.value = letter ? letter.toUpperCase() : '';
    });
    var form = input.closest('form');
    if (form) {
        form.addEventListener('submit', function () {
            var letter = (input.value || '').replace(/[^a-zA-Z]/g, '').charAt(0);
            input.value = letter ? letter.toUpperCase() + '.' : '';
        });
    }
});
</script>
