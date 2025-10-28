# Healthcare Management System - Testing Guide

## Project Objectives Testing Documentation

This guide will help you test and verify all 5 main objectives of the Healthcare Management System.

---

## 🎯 Objective 1: Enable Patients to Book Appointments

### **What to Test:**
Patients should be able to book appointments with doctors through the system.

### **How to Test:**

#### **Step 1: Login as a Patient**
1. Go to: `http://127.0.0.1:8000/login`
2. Login with patient credentials:
   - Email: `john.doe@example.com`
   - Password: `password`

#### **Step 2: Navigate to Appointment Booking**
1. Click on **"Appointments"** in the navigation menu
2. Click on **"Book New Appointment"** button
3. **Screen URL**: `http://127.0.0.1:8000/appointments/create`

#### **Step 3: Fill the Appointment Form**
1. **Hospital**: Select any hospital (e.g., "City General Hospital")
2. **Department**: Select a department (e.g., "Cardiology")
3. **Doctor**: 
   - Option A: Select a doctor manually from dropdown
   - Option B: Check "Auto-assign nearest available doctor" checkbox
4. **Appointment Type**: Select type (e.g., "Consultation")
5. **Date**: Select a future date
6. **Time Slot**: Select available time
7. **Reason for Visit**: Enter reason (e.g., "Regular checkup")
8. **Symptoms** (Optional): Enter any symptoms

#### **Step 4: Submit and Verify**
1. Click **"Book Appointment"** button
2. ✅ **Expected Result**: 
   - Success message appears
   - Redirected to appointment details page
   - Appointment shows in appointments list

#### **Screens to Check:**
- `/appointments` - View all appointments
- `/appointments/create` - Book new appointment
- `/appointments/{id}` - View appointment details

---

## 🎯 Objective 2: Allow Patients to Submit Symptoms for AI Triage

### **What to Test:**
Patients can submit their symptoms for triage assessment to determine urgency.

### **How to Test:**

#### **Step 1: Login as Patient or Nurse**
1. Go to: `http://127.0.0.1:8000/login`
2. Login with credentials:
   - **Patient**: `john.doe@example.com` / `password`
   - **Nurse**: `mary.wilson@healthcare.com` / `password`

#### **Step 2: Navigate to Triage Assessment**
1. Click on **"Triage"** in the navigation menu
2. Click on **"New Assessment"** button
3. **Screen URL**: `http://127.0.0.1:8000/triage/create`

#### **Step 3: Fill the Triage Form**
1. **Patient**: Select patient (if you're staff) or auto-filled (if you're patient)
2. **Urgency Level**: Select urgency (e.g., "Urgent", "Critical", "Standard")
3. **Chief Complaint**: Enter main complaint (e.g., "Chest pain")
4. **Symptoms Description**: Describe symptoms in detail
5. **Vital Signs**:
   - Blood Pressure: e.g., "120/80"
   - Heart Rate: e.g., "75"
   - Temperature: e.g., "98.6"
   - Respiratory Rate: e.g., "16"
   - Oxygen Saturation: e.g., "98"
6. **Pain Scale**: Select 0-10 (0 = No pain, 10 = Worst pain)
7. **Requires Immediate Attention**: Check if critical
8. **Recommended Department**: Select department
9. **Triage Notes**: Add any additional notes

#### **Step 4: Submit and Verify**
1. Click **"Submit Assessment"** button
2. ✅ **Expected Result**:
   - Success message appears
   - Priority score is calculated automatically
   - Assessment appears in triage list with color-coded urgency

#### **Screens to Check:**
- `/triage` - View all triage assessments
- `/triage/create` - Create new triage assessment
- `/triage/{id}` - View triage details

---

## 🎯 Objective 3: Classify Patients According to Urgency Levels

### **What to Test:**
System automatically calculates priority scores and classifies patients by urgency.

### **How to Test:**

#### **Step 1: Create Multiple Triage Assessments**
Follow Objective 2 steps and create assessments with different urgency levels:

1. **Critical Patient**:
   - Urgency Level: "Critical"
   - Pain Scale: 9-10
   - Check "Requires Immediate Attention"
   - ✅ **Expected Priority Score**: 90-100

2. **Urgent Patient**:
   - Urgency Level: "Urgent"
   - Pain Scale: 7-8
   - ✅ **Expected Priority Score**: 70-85

3. **Standard Patient**:
   - Urgency Level: "Standard"
   - Pain Scale: 3-5
   - ✅ **Expected Priority Score**: 30-40

4. **Non-Urgent Patient**:
   - Urgency Level: "Non-Urgent"
   - Pain Scale: 0-2
   - ✅ **Expected Priority Score**: 10-20

#### **Step 2: View Triage List**
1. Go to: `http://127.0.0.1:8000/triage`
2. ✅ **Expected Result**:
   - Patients sorted by priority score (highest first)
   - Color-coded urgency badges:
     - 🔴 **Red**: Critical
     - 🟠 **Orange**: Urgent
     - 🟡 **Yellow**: Semi-Urgent
     - 🔵 **Blue**: Standard
     - 🟢 **Green**: Non-Urgent

#### **Step 3: Verify Priority Score Calculation**
1. Click on any triage assessment
2. Check the **Priority Score** field
3. ✅ **Verify Formula**:
   ```
   Base Score (by urgency level):
   - Critical: 90
   - Urgent: 70
   - Semi-Urgent: 50
   - Standard: 30
   - Non-Urgent: 10
   
   Additional Points:
   - Pain Scale ≥ 7: +15 points
   - Pain Scale ≥ 5: +10 points
   - Requires Immediate Attention: +20 points
   
   Maximum Score: 100
   ```

#### **Screens to Check:**
- `/triage` - View sorted triage list with urgency colors
- `/triage/{id}` - View individual assessment with priority score

---

## 🎯 Objective 4: Assign Patient to Nearest Available Doctor

### **What to Test:**
System can automatically assign patients to available doctors based on workload and availability.

### **How to Test:**

#### **Step 1: Check Doctor Availability (Admin Only)**
1. Login as admin:
   - Email: `admin@healthcare.com`
   - Password: `password`
2. Go to: `http://127.0.0.1:8000/doctors-availability`
3. ✅ **Verify**: List of all doctors with availability status
4. **Toggle availability** if needed using the switch buttons

#### **Step 2: Test Manual Doctor Selection**
1. Login as patient
2. Go to: `http://127.0.0.1:8000/appointments/create`
3. Select Hospital and Department
4. **Doctor dropdown** should populate with available doctors
5. Select a doctor manually
6. Complete booking
7. ✅ **Expected Result**: Appointment booked with selected doctor

#### **Step 3: Test Auto-Assign Feature**
1. Go to: `http://127.0.0.1:8000/appointments/create`
2. Select Hospital and Department
3. **Check the box**: "Auto-assign nearest available doctor"
4. ✅ **Verify**: Doctor dropdown becomes disabled (grayed out)
5. Select Date and other details
6. Click "Book Appointment"
7. ✅ **Expected Result**:
   - System automatically assigns best available doctor
   - Success message shows assigned doctor's name
   - Doctor is from same hospital and department
   - Doctor has lowest workload for that date

#### **Step 4: Test No Available Doctors Scenario**
1. As admin, mark all doctors in a department as unavailable
2. Try to book appointment in that department with auto-assign
3. ✅ **Expected Result**: Error message suggesting to try another date or select manually

#### **Step 5: Verify Doctor Assignment Logic**
The system assigns doctors based on:
- ✅ Same hospital and department
- ✅ Doctor is marked as available (`is_available = true`)
- ✅ Doctor hasn't reached max patients per day
- ✅ Doctor with lowest current appointments for that date
- ✅ Fallback to other hospitals if no doctor available in selected hospital

#### **Screens to Check:**
- `/doctors-availability` - Admin view of doctor availability (Admin only)
- `/appointments/create` - Appointment booking with auto-assign option
- `/appointments/{id}` - View assigned doctor details

---

## 🎯 Objective 5: Notify Patients About Appointment Changes

### **What to Test:**
System sends notifications to patients and doctors for appointment-related events.

### **How to Test:**

#### **Step 1: Test Appointment Creation Notification**
1. Login as patient
2. Book a new appointment (follow Objective 1 steps)
3. ✅ **Expected Notifications**:
   - **Patient receives**: "Appointment Scheduled" notification
   - **Doctor receives**: "New Appointment" notification
4. Check notifications:
   - Click the **bell icon** 🔔 in navigation
   - Or go to: `http://127.0.0.1:8000/dashboard`

#### **Step 2: Test Appointment Update Notification**
1. Login as admin or staff
2. Go to: `http://127.0.0.1:8000/appointments`
3. Click on an appointment
4. Click **"Edit"** button
5. **Make changes**:
   - Change doctor
   - Change date/time
   - Change status
6. Click **"Update Appointment"**
7. ✅ **Expected Notifications**:
   - **Patient receives**: "Appointment Updated" with list of changes (HIGH priority)
   - **Old Doctor receives**: "Appointment Reassigned" (if doctor changed)
   - **New Doctor receives**: "New Appointment Assigned" (if doctor changed)

#### **Step 3: Test Appointment Cancellation Notification**
1. Go to an appointment details page
2. Click **"Cancel Appointment"** or **Delete** button
3. Confirm cancellation
4. ✅ **Expected Notifications**:
   - **Patient receives**: "Appointment Cancelled" (HIGH priority)
   - **Doctor receives**: "Appointment Cancelled" (MEDIUM priority)

#### **Step 4: Verify Notification Details**
1. Click on any notification
2. ✅ **Check notification contains**:
   - **Title**: Clear notification title
   - **Message**: Detailed message with date/time
   - **Type**: "appointment"
   - **Priority**: High/Medium/Low
   - **Data**: Appointment ID and relevant details
   - **Timestamp**: When notification was created

#### **Step 5: Test Notification Features**
1. Go to dashboard or notifications page
2. ✅ **Verify features**:
   - Unread notifications are highlighted
   - Click to mark as read
   - Notifications sorted by date (newest first)
   - Priority color coding:
     - 🔴 High: Red
     - 🟠 Medium: Orange
     - 🟢 Low: Green

#### **Screens to Check:**
- `/dashboard` - Dashboard with notification widget
- `/notifications` - Full notifications list (if implemented)
- Bell icon dropdown in navigation bar

---

## 📊 Complete Testing Checklist

### **User Roles for Testing**

| Role | Email | Password | Access |
|------|-------|----------|--------|
| Admin | admin@healthcare.com | password | Full system access |
| Doctor | sarah.johnson@healthcare.com | password | Doctor features |
| Nurse | mary.wilson@healthcare.com | password | Triage, patient management |
| Patient | john.doe@example.com | password | Book appointments, view records |

### **Quick Test Scenarios**

#### **Scenario 1: Complete Patient Journey**
1. ✅ Register/Login as patient
2. ✅ Submit symptoms for triage
3. ✅ Book appointment (auto-assign doctor)
4. ✅ Receive booking notification
5. ✅ View appointment details

#### **Scenario 2: Emergency Patient**
1. ✅ Create triage assessment with "Critical" urgency
2. ✅ Pain scale 10, requires immediate attention
3. ✅ Verify priority score = 100
4. ✅ Book emergency appointment
5. ✅ Verify notifications sent

#### **Scenario 3: Admin Management**
1. ✅ Login as admin
2. ✅ View doctor availability
3. ✅ Toggle doctor availability
4. ✅ View all appointments
5. ✅ Update appointment (verify notifications)
6. ✅ Cancel appointment (verify notifications)

---

## 🔍 Expected Results Summary

### **Objective 1: Appointment Booking**
- ✅ Patients can book appointments
- ✅ Form validates all required fields
- ✅ System checks doctor availability
- ✅ Success message and redirect

### **Objective 2: Symptom Submission**
- ✅ Triage form accepts all symptom data
- ✅ Vital signs recorded
- ✅ Pain scale captured
- ✅ Assessment saved successfully

### **Objective 3: Urgency Classification**
- ✅ Priority score calculated automatically
- ✅ Patients sorted by urgency
- ✅ Color-coded urgency levels
- ✅ Score range: 0-100

### **Objective 4: Doctor Assignment**
- ✅ Manual doctor selection works
- ✅ Auto-assign feature works
- ✅ Assigns based on availability and workload
- ✅ Error handling for no available doctors

### **Objective 5: Notifications**
- ✅ Notifications sent on appointment create
- ✅ Notifications sent on appointment update
- ✅ Notifications sent on appointment cancel
- ✅ Both patient and doctor notified
- ✅ Priority levels respected

---

## 🐛 Troubleshooting

### **Issue: Can't see appointments**
- **Solution**: Make sure you're logged in as the correct user role
- Check: `/appointments` URL

### **Issue: No doctors in dropdown**
- **Solution**: Admin needs to mark doctors as available
- Check: `/doctors-availability` (admin only)

### **Issue: Notifications not showing**
- **Solution**: Check database `notifications` table
- Verify: User ID matches logged-in user

### **Issue: Priority score incorrect**
- **Solution**: Review triage form inputs
- Verify: Urgency level, pain scale, immediate attention checkbox

### **Issue: Auto-assign not working**
- **Solution**: Ensure doctors are available in selected department
- Check: Doctor availability and max patients per day

---

## 📝 Notes

- All features are fully implemented and functional
- System uses real-time validation
- Notifications are stored in database
- Priority scores follow documented formula
- Doctor assignment considers workload balancing

---

## 🎉 Success Criteria

All objectives are considered **PASSED** if:

1. ✅ Patients can successfully book appointments
2. ✅ Triage assessments are created with symptoms
3. ✅ Priority scores are calculated correctly (0-100)
4. ✅ Doctors are assigned automatically or manually
5. ✅ Notifications are sent for all appointment events

---

**Last Updated**: October 28, 2025  
**System Version**: 1.0  
**Testing Status**: ✅ All Objectives Implemented
