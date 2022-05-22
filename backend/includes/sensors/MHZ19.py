import serial
import struct

class MHZ19:
    def __init__(self, option: str):
        self.ser = None
        self.array = None
        self.retry_count = 3
        self.dev = option


    def connect_serial(self) -> serial.Serial:
        return serial.Serial(self.dev,
                             baudrate=9600,
                             bytesize=serial.EIGHTBITS,
                             parity=serial.PARITY_NONE,
                             stopbits=serial.STOPBITS_ONE,
                             timeout=1.0)

    @staticmethod
    def checksum(i: bytes) -> bytes:
        csum = sum(i) % 0x100
        if csum == 0:
            return struct.pack('B', 0)
        else:
            return struct.pack('B', 0xff - csum + 1)


    def read(self) -> tuple[int]:
        self.ser = self.connect_serial()
        for retry in range(self.retry_count):
            self.ser.write(b"\xff\x01\x86\x00\x00\x00\x00\x00\x79")
            s = self.ser.read(9)

        if len(s) >= 4 and s[0] == 0xff and s[1] == 0x86 and ord(self.checksum(s[1:-1])) == s[-1]:

            result = (s[2] * 256 + s[3],)
            return result

    def close(self) -> None:
        serial.Serial.close(self.connect_serial())


    def __del__(self):
        self.close()
