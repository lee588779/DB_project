<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
	<h2><a href="http://bookdatabase.dothome.co.kr/main.php">인터넷도서사이트</a></h2>
</head>
<body>
<form>

	<!-- 회원 -->
	<p>
		<strong>아이디</strong>
			<input type="text" name="signup_id" placeholder="아이디 입력">
			<input type="submit" value="중복확인" formaction="http://bookdatabase.dothome.co.kr/check_id.php">
	</p>

	<p>
		<strong>비밀번호</strong>
		<input type="password" name="signup_pw" placeholder="비밀번호 입력">
	</p>

    <p>
		<strong>성명</strong>
		<input type="text" name="signup_name" placeholder="성명">
	</p>
	<p>
		<strong>이메일</strong>
		<input type="text" name="signup_email" placeholder="xxxx@xxxx.com">
	</p>
	<p>
		<strong>휴대폰번호</strong>
		<input type="text" name="signup_number" placeholder="01012341234">
	</p>
	<p>
		<strong>대학생여부</strong>
		<select name="studentYN" size="1">
			<option value = "Yes">Yes</option>
			<option value = "No">No</option>
		</select>
	</p>

	<!-- 신용카드정보 -->
	<p>
		<strong>카드번호</strong>
		<input type="text" name="cardNumber" placeholder="카드번호 입력">
	</p>

	<p>
		<strong>유효기간</strong>
		<input type="text" name="cardDate" placeholder="유효기간 입력">
	</p>

    <p>
		<strong>카드종류</strong>
		<input type="text" name="cardKind" placeholder="카드종류">
	</p>

	
	<!-- 배송지 -->
	<p>
		<strong>우편번호</strong>
		<input type="text" name="zipCode" placeholder="우편번호">
	</p>

	<p>
		<strong>기본주소</strong>
		<input type="text" name="defaultAddress" placeholder="기본주소">
	</p>

	<p>
		<strong>상세주소</strong>
		<input type="text" name="detailAddress" placeholder="상세주소">
	</p>
	
	<p>
		<input type="submit" value="가입하기" formaction="http://bookdatabase.dothome.co.kr/signup.php">
	</p>
</form>
</body>
</html>