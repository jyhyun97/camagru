#!/bin/bash

# .env 파일 경로
env_file=".env"

# .env 파일에서 키-값 쌍 읽기
while IFS= read -r line; do
  # 공백 및 주석 라인 제외
  if [[ "$line" =~ ^[^[:space:]] && ! "$line" =~ ^# ]]; then
    # 키-값 쌍 분리
    key=$(echo "$line" | cut -d '=' -f 1)
    value=$(echo "$line" | cut -d '=' -f 2-)

    # 환경 변수로 설정
    export "$key"="$value"
  fi
done < "$env_file"
